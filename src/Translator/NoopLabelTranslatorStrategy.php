<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\SBAdmin2Bundle\Translator;

/**
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class NoopLabelTranslatorStrategy implements LabelTranslatorStrategyInterface
{
    public function getLabel($label, $context = '', $type = '')
    {
        return $label;
    }
}
