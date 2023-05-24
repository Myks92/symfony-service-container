<?php

declare(strict_types=1);

namespace App\Auth\Test\Builder;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Name;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\Phone;
use App\Auth\Entity\User\Role;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use DateTimeImmutable;

final class UserBuilder
{
    private Id $id;
    private Name $name;
    private ?Phone $phone = null;
    private Email $email;
    private string $passwordHash;
    private DateTimeImmutable $date;
    private Token $joinConfirmToken;
    private bool $active = false;
    private ?Network $networkIdentity = null;
    /**
     * @var list<Network>
     */
    private array $networks = [];
    private ?Role $role = null;

    public function __construct()
    {
        $this->id = new Id('00000000-0000-0000-0000-000000000001');
        $this->email = new Email('mail@example.com');
        $this->name = new Name('First Name', 'Last Name');
        $this->passwordHash = 'hash';
        $this->date = new DateTimeImmutable();
        $this->joinConfirmToken = new Token('00000000-0000-0000-0000-000000000001', $this->date->modify('+1 day'));
    }

    public function withId(Id $id): self
    {
        $clone = clone $this;
        $clone->id = $id;
        return $clone;
    }

    public function withJoinConfirmToken(Token $token): self
    {
        $clone = clone $this;
        $clone->joinConfirmToken = $token;
        return $clone;
    }

    public function withEmail(Email $email): self
    {
        $clone = clone $this;
        $clone->email = $email;
        return $clone;
    }

    public function withPhone(Phone $phone): self
    {
        $clone = clone $this;
        $clone->phone = $phone;
        return $clone;
    }

    public function withRole(Role $role): self
    {
        $clone = clone $this;
        $clone->role = $role;
        return $clone;
    }

    public function withPasswordHash(string $passwordHash): self
    {
        $clone = clone $this;
        $clone->passwordHash = $passwordHash;
        return $clone;
    }

    public function viaNetwork(Network $network = null): self
    {
        $clone = clone $this;
        $clone->networkIdentity = $network ?? new Network('vk', '0000001');
        return $clone;
    }

    public function withNetwork(Network $network): self
    {
        $clone = clone $this;
        $clone->networks[] = $network;
        return $clone;
    }

    public function active(): self
    {
        $clone = clone $this;
        $clone->active = true;
        return $clone;
    }

    public function build(): User
    {
        if ($this->networkIdentity !== null) {
            $user = User::joinByNetwork(
                $this->id,
                $this->date,
                $this->name,
                $this->email,
                $this->networkIdentity,
                $this->phone,
            );
        } else {
            $user = User::requestJoinByEmail(
                $this->id,
                $this->date,
                $this->name,
                $this->email,
                $this->passwordHash,
                $this->joinConfirmToken
            );
        }

        if ($this->active) {
            $user->confirmJoin(
                $this->joinConfirmToken->getValue(),
                $this->joinConfirmToken->getExpires()->modify('-1 day')
            );
        }

        if ($this->networks !== []) {
            foreach ($this->networks as $network) {
                $user->attachNetwork($network);
            }
        }

        if ($this->role) {
            $user->changeRole($this->role);
        }

        return $user;
    }
}
