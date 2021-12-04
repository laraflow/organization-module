<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('organization.', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Organization ', route('organization.'));
});

