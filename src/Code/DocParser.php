<?php
namespace Lin\Enum\Code;

class DocParser
{
    private $params = array();

    /**
     * 解析
     * @param string $doc
     * @return array
     */
    public function parse($doc = '')
    {
        $this->params = array();
        if ($doc == '') {
            return $this->params;
        }
        // Get the comment
        if (preg_match('#^/\*\*(.*)\*/#s', $doc, $comment) === false) {
            return $this->params;
        }
        $comment = trim($comment [1]);
        // Get all the lines and strip the * from the first character
        if (preg_match_all('#^\s*\*(.*)#m', $comment, $lines) === false) {
            return $this->params;
        }
        $this->parseLines($lines [1]);
        return $this->params;
    }

    /**
     * @param $lines
     */
    private function parseLines($lines)
    {
        foreach ($lines as $line) {
            $parsedLine = $this->parseLine($line); // Parse the line

            if ($parsedLine === false && !isset($this->params ['description'])) {
                if (isset($desc)) {
                    // Store the first line in the short description
                    $this->params ['description'] = implode(PHP_EOL, $desc);
                }
                $desc = array();
            } elseif ($parsedLine !== false) {
                $desc [] = $parsedLine; // Store the line in the long description
            }
        }
        $desc = implode(' ', $desc);
        if (!empty($desc)) {
            $this->params ['long_description'] = $desc;
        }
    }

    /**
     * @param $line
     * @return bool|string
     */
    private function parseLine($line)
    {
        // trim the whitespace from the line
        $line = trim($line);

        if (empty($line)) {
            return false;
        } // Empty line

        if (strpos($line, '@') === 0) {
            if (strpos($line, '(') > 0) {
                $param = substr($line, 1, strpos($line, '(') - 1);
                $str = substr($line, strlen($param) + 3);
                $value = substr($str, 0, strpos($str, ')') - 1);
            } else {
                $param = substr($line, 1);
                $value = '';
            }

            // Parse the line and return false if the parameter is valid
            if ($this->setParam($param, $value)) {
                return false;
            }
        }

        return $line;
    }

    /**
     * @param $param
     * @param $value
     * @return bool
     */
    private function setParam($param, $value)
    {
        if ($param == 'param' || $param == 'return') {
            $value = $this->formatParamOrReturn($value);
        }
        if ($param == 'class') {
            list($param, $value) = $this->formatClass($value);
        }

        if (empty($this->params [$param])) {
            $this->params [$param] = $value;
        } elseif ($param == 'param') {
            $arr = array(
                $this->params [$param],
                $value,
            );
            $this->params [$param] = $arr;
        } else {
            $this->params [$param] = $value + $this->params [$param];
        }
        return true;
    }

    /**
     * @param $value
     * @return array
     */
    private function formatClass($value)
    {
        $r = preg_split("[\(|\)]", $value);
        if (is_array($r)) {
            $param = $r [0];
            parse_str($r [1], $value);
            foreach ($value as $key => $val) {
                $val = explode(',', $val);
                if (count($val) > 1) {
                    $value [$key] = $val;
                }
            }
        } else {
            $param = 'Unknown';
        }
        return array(
            $param,
            $value,
        );
    }

    /**
     * @param $string
     * @return string
     */
    private function formatParamOrReturn($string)
    {
        $pos = strpos($string, ' ');

        $type = substr($string, 0, $pos);
        return '(' . $type . ')' . substr($string, $pos + 1);
    }
}
