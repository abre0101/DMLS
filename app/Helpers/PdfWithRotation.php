<?php

namespace App\Helpers;

use setasign\Fpdi\Fpdi;

class PdfWithRotation extends Fpdi
{
    protected $angle = 0;
    protected $_extgstates = [];
    protected $_extgstates_objs = [];

    public function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) $x = $this->x;
        if ($y == -1) $y = $this->y;
        if ($this->angle != 0) $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle = $angle * M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf(
                'q %.5F %.5F %.5F %.5F %.5F %.5F cm 1 0 0 1 %.5F %.5F cm',
                $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy
            ));
        }
    }

    /**
     * Set transparency (alpha) for subsequent drawing operations
     *
     * @param float $alpha value between 0 (fully transparent) and 1 (fully opaque)
     * @param string $bm blend mode, usually 'Normal'
     */
    public function SetAlpha($alpha, $bm = 'Normal')
    {
        if (!isset($this->_extgstates[$alpha])) {
            $n = count($this->_extgstates) + 1;
            $this->_extgstates[$alpha] = $n;

            // Create the extended graphics state object
            $this->_extgstates_objs[$alpha] = $this->_newobj();
            $this->_out('<<');
            $this->_out('/Type /ExtGState');
            $this->_out(sprintf('/ca %.3F', $alpha)); // non-stroking alpha
            $this->_out(sprintf('/CA %.3F', $alpha)); // stroking alpha
            $this->_out('/BM /' . $bm);
            $this->_out('>>');
            $this->_out('endobj');
        }

        // Set the graphics state to this transparency level
        $id = $this->_extgstates[$alpha];
        $this->_out(sprintf('/GS%d gs', $id));
    }

    protected function _putextgstates()
    {
        foreach ($this->_extgstates as $alpha => $n) {
            $this->_newobj();
            $this->_out('<<');
            $this->_out('/Type /ExtGState');
            $this->_out(sprintf('/ca %.3F', $alpha));
            $this->_out(sprintf('/CA %.3F', $alpha));
            $this->_out('/BM /Normal');
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

    protected function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    protected function _putresources()
    {
        parent::_putresources();

        // Add ExtGStates to resources dictionary
        if (!empty($this->_extgstates)) {
            $this->_out('/ExtGState <<');
            foreach ($this->_extgstates as $alpha => $n) {
                $objId = $this->_extgstates_objs[$alpha];
                $this->_out('/GS' . $n . ' ' . $objId . ' 0 R');
            }
            $this->_out('>>');
        }
    }
}
