<?php

declare(strict_types=1);

it('serves the workbench panel', function (): void {
    $this->get('/demo')->assertOk();
});
