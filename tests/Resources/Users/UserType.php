<?php

declare(strict_types=1);

namespace Tests\Resources\Users;


enum UserType: string {
	case GUEST = 'guest';
	case USER = 'user';
	case MANAGER = 'manager';
	case ADMIN = 'admin';
}
