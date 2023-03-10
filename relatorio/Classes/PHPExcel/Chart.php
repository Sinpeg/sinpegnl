<?php

namespace PhpOffice\PhpSpreadsheet\Chart;

/**
 * PHPExcel_Chart
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category    PHPExcel
 * @package        PHPExcel_Chart
 * @copyright    Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license        http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version        ##VERSION##, ##DATE##
 */
class Chart
{
    /**
     * Chart Name
     *
     * @var string
     */
    private $name = '';

    /**
     * Worksheet
     *
     * @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     */
    private $worksheet;

    /**
     * Chart Title
     *
     */
    private ?\PhpOffice\PhpSpreadsheet\Chart\Title $title;

    /**
     * Chart Legend
     *
     */
    private ?\PhpOffice\PhpSpreadsheet\Chart\Legend $legend;

    /**
     * X-Axis Label
     *
     */
    private ?\PhpOffice\PhpSpreadsheet\Chart\Title $xAxisLabel;

    /**
     * Y-Axis Label
     *
     */
    private ?\PhpOffice\PhpSpreadsheet\Chart\Title $yAxisLabel;

    /**
     * Chart Plot Area
     *
     */
    private ?\PhpOffice\PhpSpreadsheet\Chart\PlotArea $plotArea;

    /**
     * Plot Visible Only
     *
     * @var boolean
     */
    private $plotVisibleOnly = \true;

    /**
     * Display Blanks as
     *
     * @var string
     */
    private $displayBlanksAs = '0';

    /**
     * Chart Asix Y as
     *
     */
    private ?\PhpOffice\PhpSpreadsheet\Chart\Axis $yAxis;

    /**
     * Chart Asix X as
     *
     */
    private ?\PhpOffice\PhpSpreadsheet\Chart\Axis $xAxis;

    /**
     * Chart Major Gridlines as
     *
     */
    private ?\PhpOffice\PhpSpreadsheet\Chart\GridLines $majorGridlines;

    /**
     * Chart Minor Gridlines as
     *
     */
    private ?\PhpOffice\PhpSpreadsheet\Chart\GridLines $minorGridlines;

    /**
     * Top-Left Cell Position
     *
     * @var string
     */
    private $topLeftCellRef = 'A1';


    /**
     * Top-Left X-Offset
     *
     * @var integer
     */
    private $topLeftXOffset = 0;


    /**
     * Top-Left Y-Offset
     *
     * @var integer
     */
    private $topLeftYOffset = 0;


    /**
     * Bottom-Right Cell Position
     *
     * @var string
     */
    private $bottomRightCellRef = 'A1';


    /**
     * Bottom-Right X-Offset
     *
     * @var integer
     */
    private $bottomRightXOffset = 10;


    /**
     * Bottom-Right Y-Offset
     *
     * @var integer
     */
    private $bottomRightYOffset = 10;


    /**
     * Create a new PHPExcel_Chart
     */
    public function __construct($name, \PhpOffice\PhpSpreadsheet\Chart\Title $title = \null, \PhpOffice\PhpSpreadsheet\Chart\Legend $legend = \null, \PhpOffice\PhpSpreadsheet\Chart\PlotArea $plotArea = \null, $plotVisibleOnly = \true, $displayBlanksAs = '0', \PhpOffice\PhpSpreadsheet\Chart\Title $xAxisLabel = \null, \PhpOffice\PhpSpreadsheet\Chart\Title $yAxisLabel = \null, \PhpOffice\PhpSpreadsheet\Chart\Axis $xAxis = \null, \PhpOffice\PhpSpreadsheet\Chart\Axis $yAxis = \null, \PhpOffice\PhpSpreadsheet\Chart\GridLines $majorGridlines = \null, \PhpOffice\PhpSpreadsheet\Chart\GridLines $minorGridlines = \null)
    {
        $this->name = $name;
        $this->title = $title;
        $this->legend = $legend;
        $this->xAxisLabel = $xAxisLabel;
        $this->yAxisLabel = $yAxisLabel;
        $this->plotArea = $plotArea;
        $this->plotVisibleOnly = $plotVisibleOnly;
        $this->displayBlanksAs = $displayBlanksAs;
        $this->xAxis = $xAxis;
        $this->yAxis = $yAxis;
        $this->majorGridlines = $majorGridlines;
        $this->minorGridlines = $minorGridlines;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Worksheet
     *
     * @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     */
    public function getWorksheet()
    {
        return $this->worksheet;
    }

    /**
     * Set Worksheet
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $pValue
     * @throws \PhpOffice\PhpSpreadsheet\Chart\Exception
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setWorksheet(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $pValue = \null)
    {
        $this->worksheet = $pValue;

        return $this;
    }

    /**
     * Get Title
     *
     * @return \PhpOffice\PhpSpreadsheet\Chart\Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Title
     *
     * @param \PhpOffice\PhpSpreadsheet\Chart\Title $title
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setTitle(\PhpOffice\PhpSpreadsheet\Chart\Title $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get Legend
     *
     * @return \PhpOffice\PhpSpreadsheet\Chart\Legend
     */
    public function getLegend()
    {
        return $this->legend;
    }

    /**
     * Set Legend
     *
     * @param \PhpOffice\PhpSpreadsheet\Chart\Legend $legend
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setLegend(\PhpOffice\PhpSpreadsheet\Chart\Legend $legend)
    {
        $this->legend = $legend;

        return $this;
    }

    /**
     * Get X-Axis Label
     *
     * @return \PhpOffice\PhpSpreadsheet\Chart\Title
     */
    public function getXAxisLabel()
    {
        return $this->xAxisLabel;
    }

    /**
     * Set X-Axis Label
     *
     * @param \PhpOffice\PhpSpreadsheet\Chart\Title $label
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setXAxisLabel(\PhpOffice\PhpSpreadsheet\Chart\Title $label)
    {
        $this->xAxisLabel = $label;

        return $this;
    }

    /**
     * Get Y-Axis Label
     *
     * @return \PhpOffice\PhpSpreadsheet\Chart\Title
     */
    public function getYAxisLabel()
    {
        return $this->yAxisLabel;
    }

    /**
     * Set Y-Axis Label
     *
     * @param \PhpOffice\PhpSpreadsheet\Chart\Title $label
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setYAxisLabel(\PhpOffice\PhpSpreadsheet\Chart\Title $label)
    {
        $this->yAxisLabel = $label;

        return $this;
    }

    /**
     * Get Plot Area
     *
     * @return \PhpOffice\PhpSpreadsheet\Chart\PlotArea
     */
    public function getPlotArea()
    {
        return $this->plotArea;
    }

    /**
     * Get Plot Visible Only
     *
     * @return boolean
     */
    public function getPlotVisibleOnly()
    {
        return $this->plotVisibleOnly;
    }

    /**
     * Set Plot Visible Only
     *
     * @param boolean $plotVisibleOnly
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setPlotVisibleOnly($plotVisibleOnly = \true)
    {
        $this->plotVisibleOnly = $plotVisibleOnly;

        return $this;
    }

    /**
     * Get Display Blanks as
     *
     * @return string
     */
    public function getDisplayBlanksAs()
    {
        return $this->displayBlanksAs;
    }

    /**
     * Set Display Blanks as
     *
     * @param string $displayBlanksAs
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setDisplayBlanksAs($displayBlanksAs = '0')
    {
        $this->displayBlanksAs = $displayBlanksAs;
    }


    /**
     * Get yAxis
     *
     * @return \PhpOffice\PhpSpreadsheet\Chart\Axis
     */
    public function getChartAxisY()
    {
        if ($this->yAxis !== \null) {
            return $this->yAxis;
        }

        return new \PhpOffice\PhpSpreadsheet\Chart\Axis();
    }

    /**
     * Get xAxis
     *
     * @return \PhpOffice\PhpSpreadsheet\Chart\Axis
     */
    public function getChartAxisX()
    {
        if ($this->xAxis !== \null) {
            return $this->xAxis;
        }

        return new \PhpOffice\PhpSpreadsheet\Chart\Axis();
    }

    /**
     * Get Major Gridlines
     *
     * @return \PhpOffice\PhpSpreadsheet\Chart\GridLines
     */
    public function getMajorGridlines()
    {
        if ($this->majorGridlines !== \null) {
            return $this->majorGridlines;
        }

        return new \PhpOffice\PhpSpreadsheet\Chart\GridLines();
    }

    /**
     * Get Minor Gridlines
     *
     * @return \PhpOffice\PhpSpreadsheet\Chart\GridLines
     */
    public function getMinorGridlines()
    {
        if ($this->minorGridlines !== \null) {
            return $this->minorGridlines;
        }

        return new \PhpOffice\PhpSpreadsheet\Chart\GridLines();
    }


    /**
     * Set the Top Left position for the chart
     *
     * @param    string    $cell
     * @param    integer    $xOffset
     * @param    integer    $yOffset
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setTopLeftPosition($cell, $xOffset = \null, $yOffset = \null)
    {
        $this->topLeftCellRef = $cell;
        if (!\is_null($xOffset)) {
            $this->setTopLeftXOffset($xOffset);
        }
        if (!\is_null($yOffset)) {
            $this->setTopLeftYOffset($yOffset);
        }

        return $this;
    }

    /**
     * Get the top left position of the chart
     *
     * @return array    an associative array containing the cell address, X-Offset and Y-Offset from the top left of that cell
     */
    public function getTopLeftPosition()
    {
        return array(
            'cell'    => $this->topLeftCellRef,
            'xOffset' => $this->topLeftXOffset,
            'yOffset' => $this->topLeftYOffset
        );
    }

    /**
     * Get the cell address where the top left of the chart is fixed
     *
     * @return string
     */
    public function getTopLeftCell()
    {
        return $this->topLeftCellRef;
    }

    /**
     * Set the Top Left cell position for the chart
     *
     * @param    string    $cell
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setTopLeftCell($cell)
    {
        $this->topLeftCellRef = $cell;

        return $this;
    }

    /**
     * Set the offset position within the Top Left cell for the chart
     *
     * @param    integer    $xOffset
     * @param    integer    $yOffset
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setTopLeftOffset($xOffset = \null, $yOffset = \null)
    {
        if (!\is_null($xOffset)) {
            $this->setTopLeftXOffset($xOffset);
        }
        if (!\is_null($yOffset)) {
            $this->setTopLeftYOffset($yOffset);
        }

        return $this;
    }

    /**
     * Get the offset position within the Top Left cell for the chart
     *
     * @return integer[]
     */
    public function getTopLeftOffset()
    {
        return array(
            'X' => $this->topLeftXOffset,
            'Y' => $this->topLeftYOffset
        );
    }

    public function setTopLeftXOffset($xOffset)
    {
        $this->topLeftXOffset = $xOffset;

        return $this;
    }

    public function getTopLeftXOffset()
    {
        return $this->topLeftXOffset;
    }

    public function setTopLeftYOffset($yOffset)
    {
        $this->topLeftYOffset = $yOffset;

        return $this;
    }

    public function getTopLeftYOffset()
    {
        return $this->topLeftYOffset;
    }

    /**
     * Set the Bottom Right position of the chart
     *
     * @param    string    $cell
     * @param    integer    $xOffset
     * @param    integer    $yOffset
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setBottomRightPosition($cell, $xOffset = \null, $yOffset = \null)
    {
        $this->bottomRightCellRef = $cell;
        if (!\is_null($xOffset)) {
            $this->setBottomRightXOffset($xOffset);
        }
        if (!\is_null($yOffset)) {
            $this->setBottomRightYOffset($yOffset);
        }

        return $this;
    }

    /**
     * Get the bottom right position of the chart
     *
     * @return array    an associative array containing the cell address, X-Offset and Y-Offset from the top left of that cell
     */
    public function getBottomRightPosition()
    {
        return array(
            'cell'    => $this->bottomRightCellRef,
            'xOffset' => $this->bottomRightXOffset,
            'yOffset' => $this->bottomRightYOffset
        );
    }

    public function setBottomRightCell($cell)
    {
        $this->bottomRightCellRef = $cell;

        return $this;
    }

    /**
     * Get the cell address where the bottom right of the chart is fixed
     *
     * @return string
     */
    public function getBottomRightCell()
    {
        return $this->bottomRightCellRef;
    }

    /**
     * Set the offset position within the Bottom Right cell for the chart
     *
     * @param    integer    $xOffset
     * @param    integer    $yOffset
     * @return \PhpOffice\PhpSpreadsheet\Chart\Chart
     */
    public function setBottomRightOffset($xOffset = \null, $yOffset = \null)
    {
        if (!\is_null($xOffset)) {
            $this->setBottomRightXOffset($xOffset);
        }
        if (!\is_null($yOffset)) {
            $this->setBottomRightYOffset($yOffset);
        }

        return $this;
    }

    /**
     * Get the offset position within the Bottom Right cell for the chart
     *
     * @return integer[]
     */
    public function getBottomRightOffset()
    {
        return array(
            'X' => $this->bottomRightXOffset,
            'Y' => $this->bottomRightYOffset
        );
    }

    public function setBottomRightXOffset($xOffset)
    {
        $this->bottomRightXOffset = $xOffset;

        return $this;
    }

    public function getBottomRightXOffset()
    {
        return $this->bottomRightXOffset;
    }

    public function setBottomRightYOffset($yOffset)
    {
        $this->bottomRightYOffset = $yOffset;

        return $this;
    }

    public function getBottomRightYOffset()
    {
        return $this->bottomRightYOffset;
    }


    public function refresh()
    {
        if ($this->worksheet !== \null) {
            $this->plotArea->refresh($this->worksheet);
        }
    }

    public function render($outputDestination = \null)
    {
        $libraryName = \PhpOffice\PhpSpreadsheet\Settings::getChartRendererName();
        if (\is_null($libraryName)) {
            return \false;
        }
        //    Ensure that data series values are up-to-date before we render
        $this->refresh();

        $libraryPath = \PhpOffice\PhpSpreadsheet\Settings::getChartRendererPath();
        $includePath = \str_replace('\\', '/', \get_include_path());
        $rendererPath = \str_replace('\\', '/', $libraryPath);
        if (\strpos($rendererPath, $includePath) === \false) {
            \set_include_path(\get_include_path() . \PATH_SEPARATOR . $libraryPath);
        }

        $rendererName = 'PHPExcel_Chart_Renderer_'.$libraryName;
        $renderer = new $rendererName($this);

        if ($outputDestination == 'php://output') {
            $outputDestination = \null;
        }
        return $renderer->render($outputDestination);
    }
}
