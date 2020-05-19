AutoTill
========

AutoTill is a multi-mode denomination counter in USD. The three modes are "Casino" (because VEGAS), which features large denominations up to 5000 (think in terms of casino chips). The other two modes are "Standard" which is a typical retail till consisting of all denominations from $100
 to $0.01, and "Small" mode which models a till found in fast food restaurants: (ex. "We do not accept bills larger than $20.")
 
The standard mode is the default set when creating a new instance of the AutoTill class:
    $my_till = new AutoTill();
 
There are 5 methods; 
    set_mode() 
takes one string argument; ['casino', 'standard', 'small'], returns true.
 
    get_mode() 
takes zero arguments and returns the instance setting string value.
 
    calc_change() 
takes two arguments, the total due and the total paid and calculates the difference. It can accept strings like "$2,632.66", or integers or float values like 456.00. It returns the difference of the two arguments, including a negative value if a balance remains.
 
    count_change() 
takes one argument; the final value as a string, int or float value. In this way, it also works like an informal bill breaker. It returns an associative array of the count of each denomination from largest to smallest, or a message indicating that a balance remains and no change is due.
 
    make_float() 
is a helper function that is used to sanitize and normalize the input values into float typed values. Takes a single argument and returns a float (or double, as the case may be) typed value, or zero (or false) if the input cannot be converted.
 
AutoTill includes an API example that takes two parameters: 'val' (required): the value to covert into change, 'mode' (optional): the mode of the conversion. If not provided, the API defaults to "standard" mode.