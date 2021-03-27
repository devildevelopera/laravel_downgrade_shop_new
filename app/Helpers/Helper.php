<?php



namespace DownGrade\Helpers;

use Cookie;

use DownGrade\Models\Members;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Redirect;

use DownGrade\Models\Languages;



class Helper 

{

    

    public static function count_rating($rate_var) 

    {

	   

	    if(count($rate_var) != 0)

        {

           $top = 0;

           $bottom = 0;

           foreach($rate_var as $view)

           { 

              if($view->rating == 1){ $value1 = $view->rating*1; } else { $value1 = 0; }

              if($view->rating == 2){ $value2 = $view->rating*2; } else { $value2 = 0; }

              if($view->rating == 3){ $value3 = $view->rating*3; } else { $value3 = 0; }

              if($view->rating == 4){ $value4 = $view->rating*4; } else { $value4 = 0; }

              if($view->rating == 5){ $value5 = $view->rating*5; } else { $value5 = 0; }

              $top += $value1 + $value2 + $value3 + $value4 + $value5;

              $bottom += $view->rating;

           }

           if(!empty(round($top/$bottom)))

           {

             $count_rating = round($top/$bottom);

           }

           else

           {

              $count_rating = 0;

            }

        }

        else

        {

            $count_rating = 0;

        }  

	    

	    

		return $count_rating;

        

    }

	

    public static function price_info($flash_var,$price_var, $percentage_var) 

    {

	    if($flash_var == 1)

        {

        $price = round($price_var - $price_var*$percentage_var/100);

        }

        else

        {

        $price = $price_var;

        }

		return $price;

	}

	

	public static function translation($id,$code) 

    {

	

	    if($code == 'en')

		{

		   $tran_value['view'] = Languages::en_Translate($id,$code);

		}

		else

		{

		  $tran_value['view'] = Languages::other_Translate($id,$code);

		}

		return $tran_value['view']->keyword_text;

        

    }

	

}