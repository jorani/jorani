<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant les employés dans une liste personnalisée (table 'org_lists_employees').
 */
#[ORM\Entity]
#[ORM\Table(name: 'org_lists_employees')]
class OrgListEmployee
{
    #[ORM\Id]
    #[ORM\Column(name: 'list', type: 'integer')]
    private int $list;

    #[ORM\Id]
    #[ORM\Column(name: 'user', type: 'integer')]
    private int $user;

    #[ORM\Column(name: 'orderlist', type: 'integer')]
    private int $orderlist;

    public function getList(): int
    {
        return $this->list;
    }

    public function setList(int $list): self
    {
        $this->list = $list;
        return $this;
    }

    public function getUser(): int
    {
        return $this->user;
    }

    public function setUser(int $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getOrderlist(): int
    {
        return $this->orderlist;
    }

    public function setOrderlist(int $orderlist): self
    {
        $this->orderlist = $orderlist;
        return $this;
    }
}
