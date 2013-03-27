<?php
namespace Aperophp\Lib;

class AutoLinkTwigExtension extends \Twig_Extension
{

    /**
     *
     * @see http://trac.symfony-project.org/browser/branches/1.4/lib/helper/TextHelper.php
     */
    public function getFilters()
    {

      $filter = new \Twig_SimpleFilter('autolink', function ($text, $truncate = false, $truncate_len = 40, $pad = '...') {
        $callback_function = '
          if (preg_match("/<a\s/i", $matches[1]))
          {
            return $matches[0];
          }
          ';

        if ($truncate)
        {
          $callback_function .= '
            else if (strlen($matches[2].$matches[3]) > '.$truncate_len.')
            {
              return $matches[1].\'<a href="\'.($matches[2] == "www." ? "http://www." : $matches[2]).$matches[3].\'">\'.substr($matches[2].$matches[3], 0, '.$truncate_len.').\''.$pad.'</a>\'.$matches[4];
            }
            ';
        }

        $callback_function .= '
          else
          {
            return $matches[1].\'<a href="\'.($matches[2] == "www." ? "http://www." : $matches[2]).$matches[3].\'">\'.$matches[2].$matches[3].\'</a>\'.$matches[4];
          }
          ';


        $autoLinkRe = '~
          (                       # leading text
            <\w+.*?>|             #   leading HTML tag, or
            [^=!:\'"/]|           #   leading punctuation, or
            ^                     #   beginning of line
          )
          (
            (?:https?://)|        # protocol spec, or
            (?:www\.)             # www.*
          )
          (
            [-\w]+                   # subdomain or domain
            (?:\.[-\w]+)*            # remaining subdomains or domain
            (?::\d+)?                # port
            (?:/(?:(?:[\~\w\+%-]|(?:[,.;:][^\s$]))+)?)* # path
            (?:\?[\w\+%&=.;-]+)?     # query string
            (?:\#[\w\-/\?!=]*)?        # trailing anchor
          )
          ([[:punct:]]|\s|<|$)    # trailing text
         ~x';

        return preg_replace_callback(
          $autoLinkRe,
          create_function('$matches', $callback_function),
          $text
        );


      });
      return array(
        'autolink' => $filter,
      );
    }

    public function getName()
    {
        return "autolink";
    }

}
