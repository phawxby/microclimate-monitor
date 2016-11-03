<?php
class Helpers 
{
  public static function makeUniqueShortName($short_name, $haystack) 
  {
      $short_name = preg_replace("/[^A-Za-z0-9\-_]/", '', $short_name);
      $unique_short_name = $short_name;

      for($i = 0; $i <= 20; $i++) 
      {
          $unique_short_name = $short_name . ($i <= 0 ? "" : $i);
          
          if (!in_array($unique_short_name, $haystack)) {
            return $unique_short_name;
          }

          if ($i >= 20) { 
              throw new Exception('Could not create unique short_name.');
          }
      }

      return null;
  }
}
?>