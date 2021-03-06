<?php

/*
 * This file is part of the Ubirak package.
 *
 * (c) Ubirak team <team@ubirak.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ubirak\Component\Healthcheck;

interface Healthcheck
{
    /**
     * Informs if a destination is reachable
     *
     * @param Destination    $destination  A destination to join for the health check
     *
     * @throws HealthcheckFailure when a non expected health check failure occurs.
     */
    public function isReachable(Destination $destination): bool;
}
