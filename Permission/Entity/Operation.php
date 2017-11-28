<?php

namespace AOS\Security\Crud\Permission\Entity;

class Operation
{
    const READ = 1;           // 1 << 0
    const CREATE = 2;         // 1 << 1
    const UPDATE = 4;         // 1 << 2
    const DELETE = 8;         // 1 << 3
}
