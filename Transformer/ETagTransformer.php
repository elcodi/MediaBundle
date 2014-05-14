<?php

/**
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  * @version  */

namespace Elcodi\MediaBundle\Transformer;

/**
 * Class ETagTransformer
 */
class ETagTransformer
{
    /**
     * Transforms an entity to be stored
     *
     * @param FileInterface $file File to transform
     *
     * @return mixed Entity transformed
     *
     * @api
     */
    public function transform(FileInterface $file)
    {
        return  $file->getId() . '_' .
                $file->getExtension();
    }
}
