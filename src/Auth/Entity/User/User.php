<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Auth\Service\PasswordHasher;
use App\Shared\Aggregate\AggregateRoot;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'auth_users')]
final class User extends AggregateRoot
{
    #[ORM\Column(type: 'auth_user_id')]
    #[ORM\Id]
    private Id $id;
    #[ORM\Embedded(class: Name::class)]
    private Name $name;
    #[ORM\Column(type: 'auth_user_phone', length: 30, unique: true, nullable: true)]
    private ?Phone $phone = null;
    #[ORM\Column(type: 'auth_user_email', unique: true)]
    private Email $email;
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $passwordHash = null;
    #[ORM\Column(type: 'auth_user_status', length: 16)]
    private Status $status;
    #[ORM\Embedded(class: Token::class)]
    private ?Token $joinConfirmToken = null;
    #[ORM\Embedded(class: Token::class)]
    private ?Token $passwordResetToken = null;
    #[ORM\Column(type: 'auth_user_email', nullable: true)]
    private ?Email $newEmail = null;
    #[ORM\Embedded(class: Token::class)]
    private ?Token $newEmailToken = null;
    #[ORM\Column(type: 'auth_user_role', length: 16)]
    private Role $role;
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createDate;
    /**
     * @var Collection<array-key, UserNetwork>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserNetwork::class, cascade: ['all'], orphanRemoval: true)]
    private Collection $networks;

    private function __construct(
        Id $id,
        DateTimeImmutable $date,
        Name $name,
        Email $email,
        Status $status
    ) {
        $this->id = $id;
        $this->createDate = $date;
        $this->name = $name;
        $this->email = $email;
        $this->status = $status;
        $this->role = Role::user();
        $this->networks = new ArrayCollection();
    }

    public static function joinByNetwork(
        Id $id,
        DateTimeImmutable $date,
        Name $name,
        Email $email,
        Network $network,
        ?Phone $phone = null,
    ): self {
        $user = new self($id, $date, $name, $email, Status::active());
        $user->phone = $phone;
        $user->networks->add(new UserNetwork($user, $network));
        return $user;
    }

    public static function requestJoinByEmail(
        Id $id,
        DateTimeImmutable $date,
        Name $name,
        Email $email,
        string $passwordHash,
        Token $token
    ): self {
        $user = new self($id, $date, $name, $email, Status::wait());
        $user->passwordHash = $passwordHash;
        $user->joinConfirmToken = $token;
        return $user;
    }

    public function confirmJoin(string $token, DateTimeImmutable $date): void
    {
        if ($this->joinConfirmToken === null) {
            throw new DomainException('Confirmation is not required.');
        }
        $this->joinConfirmToken->validate($token, $date);
        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }

    public function attachNetwork(Network $network): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->getNetwork()->isEqualTo($network)) {
                throw new DomainException('Network is already attached.');
            }
        }
        $this->networks->add(new UserNetwork($this, $network));
    }

    public function detachNetwork(Network $network): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->getNetwork()->isEqualTo($network)) {
                if ($this->passwordHash === null && $this->networks->count() === 1) {
                    throw new DomainException('Unable to detach the last identity.');
                }
                $this->networks->removeElement($existing);
                return;
            }
        }
        throw new DomainException('Network is not not found.');
    }

    public function requestPasswordReset(Token $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if ($this->passwordResetToken !== null && !$this->passwordResetToken->isExpiredTo($date)) {
            throw new DomainException('Resetting is already requested.');
        }
        $this->passwordResetToken = $token;
    }

    public function resetPassword(string $token, DateTimeImmutable $date, string $hash): void
    {
        if ($this->passwordResetToken === null) {
            throw new DomainException('Resetting is not requested.');
        }
        $this->passwordResetToken->validate($token, $date);
        $this->passwordResetToken = null;
        $this->passwordHash = $hash;
    }

    public function changePassword(string $current, string $new, PasswordHasher $hasher): void
    {
        if ($this->passwordHash === null) {
            throw new DomainException('User does not have an old password.');
        }
        if (!$hasher->validate($current, $this->passwordHash)) {
            throw new DomainException('Incorrect current password.');
        }
        $this->passwordHash = $hasher->hash($new);
    }

    public function requestEmailChanging(Token $token, DateTimeImmutable $date, Email $email): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if ($this->email->isEqual($email)) {
            throw new DomainException('Email is already same.');
        }
        if ($this->newEmailToken !== null && !$this->newEmailToken->isExpiredTo($date)) {
            throw new DomainException('Changing is already requested.');
        }
        $this->newEmail = $email;
        $this->newEmailToken = $token;
    }

    public function confirmEmailChanging(string $token, DateTimeImmutable $date): void
    {
        if ($this->newEmail === null || $this->newEmailToken === null) {
            throw new DomainException('Changing is not requested.');
        }
        $this->newEmailToken->validate($token, $date);
        $this->email = $this->newEmail;
        $this->newEmail = null;
        $this->newEmailToken = null;
    }

    public function changeRole(Role $role): void
    {
        $this->role = $role;
    }

    public function remove(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('Unable to remove active user.');
        }
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getCreateDate(): DateTimeImmutable
    {
        return $this->createDate;
    }

    public  function getName(): Name
    {
        return $this->name;
    }

    public  function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    public function getPasswordResetToken(): ?Token
    {
        return $this->passwordResetToken;
    }

    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    public function getNewEmailToken(): ?Token
    {
        return $this->newEmailToken;
    }

    /**
     * @return list<Network>
     */
    public function getNetworks(): array
    {
        /** @var list<Network> */
        return $this->networks->map(static fn (UserNetwork $network) => $network->getNetwork())->toArray();
    }

    #[ORM\PostLoad]
    public function checkEmbeds(): void
    {
        if ($this->joinConfirmToken && $this->joinConfirmToken->isEmpty()) {
            $this->joinConfirmToken = null;
        }
        if ($this->passwordResetToken && $this->passwordResetToken->isEmpty()) {
            $this->passwordResetToken = null;
        }
        if ($this->newEmailToken && $this->newEmailToken->isEmpty()) {
            $this->newEmailToken = null;
        }
    }
}
