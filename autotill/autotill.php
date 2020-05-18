<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class AutoTill {     
  
  const mode_options  = array('casino', 'standard', 'small');
  
  const denominations = array(
    'casino'  => array(5000, 2000, 1000, 500, 250, 100, 50, 25, 20, 10, 5, 1, 0.5, 0.25, 0.1, 0.05, 0.01),
    'standard' => array(100, 50, 20, 10, 5, 1, 0.25, 0.1, 0.05, 0.01),
    'small'    => array(20, 10, 5, 1, 0.25, 0.1, 0.05, 0.01)
  );

  public $mode;
  public $price;
  public $paid;
  public $change;

  /* CONSTRUCTOR */
  public function __construct() { 
    $this->mode = self::mode_options[0]; 
  } 

  /* Member Functions */

  # MODE SETTER
  public function setMode($mode){
    // Sets $mode string to lowercase 
    $mode = strtolower($mode);
    /*  
     * Tests mode value against pre-set mode options,
     * if not found, throws NOTICE and sets the mode to 'standard'.
     */
    if ( !in_array($mode, self::mode_options) ) {
      $this->mode_err(8, $mode);
      $this->mode = 'standard';
    } else {
      $this->mode = $mode;
    }
  }
   
  # MODE GETTER
  public function getMode(){
    echo $this->mode;
  }

  # CHANGE CALCULATOR
  public function calc_change($price, $paid) {
    
    // float value handlers
    $price  = $this->make_float($price);
    $paid   = $this->make_float($paid);

    /* Simple math */
    $balance = $paid - $price;

    return $balance;
  }

  # BILL COUNTER (also doubles as a bill breaker)
  public function count_bills($balance) {
    
    // Make sure we're still working with a POSITIVE float value
    $balance = $this->make_float($balance);
    
    if ($balance > 0) {

      // Set denomination mode
      $dNomObj = self::denominations[$this->mode];

      // Count the change
      
      $n = 0;
      while ($balance > 0) {
        $denomNum = (float)$dNomObj[$n];
        $denomStr = (string)$dNomObj[$n];

        $change[$denomStr] = floor($balance/$denomNum);
        $balance = round($balance - ($denomNum * (int)$change[$denomStr]) , 2);      
        $n++;
      }
      return $change;
    
    } else {
      return '$balance balance remaining, no change due';
    }
  }

  /* Private Members */
  private function mode_err($errlvl, $modeval) {
    echo "NOTICE: [$errlvl] '$modeval' not a valid mode; Options are ['standard' <em>(default)</em>, 'small', 'casino']. Setting mode to 'standard.'<br>";
  }

  /* Converts various string dollar input values to float numbers */
  private function make_float($input) {
    $removals = '/[,$]/m';
    $input = round(preg_replace(trim($removals), '', $input), 2);
    return $input;
  }

}
 

/* TEST CODE */
$my_till = new AutoTill();  
$my_till->setMode('casino');

$balance = $my_till->calc_change('$12.19', 200)."<br>";
$balance = '$234,432,432.44';

echo $balance;
echo "<pre>";
print_r($my_till->count_bills($balance));
echo "</pre>";


?> 