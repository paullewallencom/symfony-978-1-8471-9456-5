<?php

/**
 * best actions.
 *
 * @package    milkshake
 * @subpackage best
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class bestActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    //Get the results
  	$milkshakeArray = MilkshakePeer::getBestMilkshakes();

  	//print_r($milkshakeArray[0]->getName());

    foreach($milkshakeArray as $key => $val)
  	{
  		$viewArray[$val->getName()] = $val->getViews();
  	}


	  $graph = new ezcGraphPieChart();
  //$graph->palette = new ezcGraphPaletteWhite();
$graph->title = 'Favourite Flavours';
	  $graph->title->maxHeight = 0.1;
    $graph->legend = false;
	  $graph->data['Access statistics'] = new ezcGraphArrayDataSet($viewArray);


    $graph->driver = new ezcGraphGdDriver();
		$graph->options->font = '/usr/share/fonts/truetype/msttcorefonts/arial.ttf';
		$graph->driver->options->supersampling = 1;
		$graph->driver->options->jpegQuality = 100;
		$graph->driver->options->imageFormat = IMG_JPEG;


    $graph->renderer->options->pieChartGleam = .3;
    $graph->renderer->options->pieChartGleamColor = '#FFFFFF';
    $graph->renderer->options->pieChartGleamBorder = 2;
    $graph->renderer->options->pieChartShadowSize = 5;
    $graph->renderer->options->pieChartShadowColor = '#BABDB6';


 $graph->renderer->options->legendSymbolGleam = .5;
 $graph->renderer->options->legendSymbolGleamSize = .9;
$graph->renderer->options->legendSymbolGleamColor = '#FFFFFF';

    $graph->renderer->options->pieChartSymbolColor = '#BABDB688';
		//render image
		$graph->render(560, 500, 'images/charts/pie_chart.jpg' );


		return sfView::SUCCESS;

	
  }
}
