<?php 

///*
ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//*/

/*
 * AutoTill is a multi-mode denomination counter in USD.
 * The three modes are "Casino" (because VEGAS), which 
 * features large denominations up to 5000 (think in 
 * terms of casino chips). 
 * The other two modes are "Standard" which is a typical
 * retail till consisting of all denominations from $100
 * to $0.01, and "Small" mode which models a till found
 * in fast food restaurants: 
 * (ex. "We do not accept bills larger than $20.") 
 * 
 * The standard mode is the default set when
 * creating a new instance of the AutoTill class:
 * $my_till = new AutoTill();
 * 
 * There are 5 methods; 
 *  set_mode() takes one string argument; 
 *    ['casino', 'standard', 'small'], returns true.
 * 
 *  get_mode() takes zero arguments and returns the 
 *    instance setting string value.
 * 
 *  calc_change() takes two arguments, the total due and 
 *    the total paid and calculates the difference.
 *    it can accept strings like, "$2,632.66" or integers
 *    or float values like 456.00. It returns the difference
 *    of the two arguments, including a negative value if
 *    a balance remains.
 * 
 *  count_change() takes one argument; the final value as
 *    a string, int or float value. In this way, it also works
 *    like an informal bill breaker. It returns an associative
 *    array of the count of each denomination from largest 
 *    to smallest, or a message indicating that a balance remains
 *    and no change is due.
 * 
 *  make_float() is a helper function that is used to sanitize and
 *    normalize the input values into float typed values. Takes a
 *    single argument and returns a float (or double, as the case 
 *    may be) typed value, or zero (or false) if the input cannot 
 *    be converted.
 * 
 *  AutoTill includes an API example that takes two parameters:
 *    'val' (required): the value to covert into change,
 *    'mode' (optional): the mode of the conversion. If not provided, 
 *     the API defaults to "standard" mode.
 */
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
    set_error_handler( array($this, 'mode_err') );
  } 

  /* Member Functions */

  # MODE SETTER
  public function set_mode($mode){
    // Sets $mode string to lowercase 
    $mode = strtolower($mode);
    /*  
     * Tests mode value against pre-set mode options,
     * if not found, throws NOTICE and sets the mode to 'standard'.
     */
    if ( !in_array($mode, self::mode_options) ) {
      trigger_error("'$mode' is not a valid mode; Options are ['standard', 'small', 'casino']. Setting mode to 'standard.'<br />\n", E_USER_NOTICE);
      $this->mode = 'standard';
    } else {
      $this->mode = $mode;
    }
    return true;
  }
   
  # MODE GETTER
  public function get_mode(){
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
  public function count_change($balance) {
    
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
      return $balance.' balance remaining, no change due.';
    }
  }

  # Helper function: Converts various string dollar input values to float numbers
  public function make_float($input) {
    $removals = '/[,$]/m';
    $input = round(preg_replace(trim($removals), '', $input), 2);
    return $input;
  }


  /* Private member functions; error handler */
  private function mode_err($errlvl, $errmsg) {
    echo "NOTICE: [$errlvl] : $errmsg";
    return true;
  }

}
 

/* EXAMPLE TEST CODE *
$my_till = new AutoTill();  
$my_till->set_mode('craps');

//$balance = $my_till->calc_change('$12.19', 100)."<br>";
$balance = '$234,432,432.44';

echo $balance;
echo "<pre>";
print_r($my_till->count_change($balance));
echo "</pre>";
//*/

?> 