<?php

namespace App\Helpers;

use setasign\Fpdi\Fpdi;

class AlphaPDF extends Fpdi
{
    protected $extgstates = [];

    function SetAlpha($alpha, $bm = 'Normal')
    {
        // Set transparency for the next elements
        $gs = $this->AddExtGState(['ca' => $alpha, 'CA' => $alpha, 'BM' => '/' . $bm]);
        $this->SetExtGState($gs);
    }

    protected function AddExtGState($parms)
    {
        $n = count($this->extgstates) + 1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    protected function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    function _enddoc()
    {
        if (!empty($this->extgstates) && $this->PDFVersion < '1.4') {
            $this->PDFVersion = '1.4';
        }
        parent::_enddoc();
    }

    function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_out('/ExtGState <<');
        foreach ($this->extgstates as $k => $extgstate) {
            $this->_out('/GS' . $k . ' ' . ($this->n + $k) . ' 0 R');
        }
        $this->_out('>>');
    }

    function _putextgstates()
    {
        foreach ($this->extgstates as $k => $extgstate) {
            $this->_newobj();
            $this->_out('<</Type /ExtGState');
            foreach ($extgstate['parms'] as $key => $value) {
                $this->_out('/' . $key . ' ' . (is_numeric($value) ? $value : $value));
            }
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

    function _putresources()
    {
        parent::_putresources();
        $this->_putextgstates();
    }

    // ROTATION SUPPORT
    protected $angle = 0;

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) $x = $this->x;
        if ($y == -1) $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.3F %.3F %.3F %.3F %.3F %.3F cm 1 0 0 1 %.3F %.3F cm',
                $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }
}
