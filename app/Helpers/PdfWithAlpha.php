<?php

namespace App\Helpers;

use setasign\Fpdi\Fpdi;

class PdfWithAlpha extends Fpdi
{
    protected $alpha = 1.0;
    protected $extGStates = [];

    public function SetAlpha($alpha, $bm = 'Normal')
    {
        $this->alpha = $alpha;

        if ($alpha < 1) {
            $gs = $this->AddExtGState(['ca' => $alpha, 'CA' => $alpha, 'BM' => '/' . $bm]);
            $this->SetExtGState($gs);
        } else {
            $this->SetExtGState();
        }
    }

    protected function AddExtGState($parms)
    {
        $n = count($this->extGStates) + 1;
        $this->extGStates[$n]['parms'] = $parms;
        return $n;
    }

    protected function SetExtGState($gs = null)
    {
        if ($gs) {
            $this->_out(sprintf('/GS%d gs', $gs));
        } else {
            $this->_out('q');
            $this->_out('Q');
        }
    }

    protected function _enddoc()
    {
        if (!empty($this->extGStates)) {
            $this->_newobj();
            $this->extGStatesObjNum = $this->n;
            $this->_out('<<');
            foreach ($this->extGStates as $k => $extGState) {
                $this->_out('/GS' . $k . ' ' . ($this->n + $k) . ' 0 R');
            }
            $this->_out('>>');
            $this->_out('endobj');

            foreach ($this->extGStates as $extGState) {
                $this->_newobj();
                $this->_out('<< /Type /ExtGState');
                foreach ($extGState['parms'] as $k => $v) {
                    $this->_out(' /' . $k . ' ' . (is_numeric($v) ? $v : $v));
                }
                $this->_out('>>');
                $this->_out('endobj');
            }
        }

        parent::_enddoc();
    }
}
