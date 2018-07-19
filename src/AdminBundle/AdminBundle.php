<?php

namespace AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use AdminBundle\DependencyInjection\AdminBundleExtension;

class AdminBundle extends Bundle {
    public function getContainerExtension() {
        return new AdminBundleExtension();
    }
}
