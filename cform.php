<?php

class cform
{
    public function bldHeader($tytle) {
        echo '<!DOCTYPE html>' . "\n";
        echo '<html lang="ru">' . "\n";
        echo '<head>' . "\n";
        echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />' . "\n";
        echo '<title>' . $tytle . '</title>' . "\n";
        echo '<link href="./css/bootstrap.css" rel="stylesheet">' . "\n";
        echo '</head>' . "\n";
        echo '<body>' . "\n";
        
    }
    public function bldFutter() {
        echo '</body>'."\n";
        echo '</html>'."\n";
    }
    /*
     * $menu = [['Имя', 'class(active)', 'url'], [...]];
     * $menuridht = ['Имя', 'class(active)', 'url'];
     */
    public function bldMenu($menu, $menuridht) {
        echo '<nav role="navigation" class="navbar navbar-default">                                                '."\n";
        echo '  <!-- Brand and toggle get grouped for better mobile display -->                                    '."\n";
        echo '  <div class="navbar-header">                                                                        '."\n";
        echo '    <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">'."\n";
        echo '      <span class="sr-only">Toggle navigation</span>                                                 '."\n";
        echo '      <span class="icon-bar"></span>                                                                 '."\n";
        echo '      <span class="icon-bar"></span>                                                                 '."\n";
        echo '      <span class="icon-bar"></span>                                                                 '."\n";
        echo '    </button>                                                                                        '."\n";
        /*echo '    <a href="#" class="navbar-brand">Бренд</a>                                                       '."\n";*/
        echo '  </div>                                                                                             '."\n";
        echo '  <!-- Collection of nav links, forms, and other content for toggling -->                            '."\n";
        echo '  <div id="navbarCollapse" class="collapse navbar-collapse">                                         '."\n";
        echo '    <ul class="nav navbar-nav">                                                                      '."\n";
        foreach($menu as $key=>$m) {
            echo '<li class="' . $m[1] . '"><a href="' . $m[2] . '">' . $m[0] . '</a></li>                                                '."\n";
        }
        echo '    </ul>                                                                                            '."\n";
        echo '    <ul class="nav navbar-nav navbar-right">                                                         '."\n";
        echo '<li class="' . $menuridht[1] . '"><a href="' . $menuridht[2] . '">' . $menuridht[0] . '</a></li>                                                '."\n";
        echo '    </ul>                                                                                            '."\n";
        echo '  </div>                                                                                             '."\n";
        echo '</nav>                                                                                               '."\n";
    }
    /*
     * $fields[]  = [lsbel, lsbelwidth, name, namewidth, type, value, errormessage, readonly]
     * $buttons[] = ['Отмена', 'button', 'btn btn-default', './'];
     */
    public function bldForm( $tytle, $tytleform, $width, $fields, $buttons) {
        $widthlr = (12 - $width) / 2;
        echo '<div class="container">'."\n";
        echo ' <div class="row row-offcanvas row-offcanvas-center">'."\n";
        echo ' <div class="col-xs-12 col-sm-12 col-md-12"><h3 align="center">' . $tytle . '</h3></div>'."\n";
        echo ' <div class="col-xs-' . $widthlr . ' col-sm-' . $widthlr . ' col-md-' . $widthlr . '"></div>' . "\n";
        echo '  <div class="col-xs-' . $width . ' col-sm-' . $width . ' col-md-' . $width . '">' . "\n";
        echo '   <div class="panel panel-info">'."\n";
        echo '    <div class="panel-heading">'."\n";
        echo $tytleform . "\n";
        echo '    </div>'."\n";
        echo '    <div class="panel-body">'."\n";
        echo '     <form class="form-horizontal" action="" method="post" onSubmit="">'."\n";
        echo '     <input type="hidden" name="action" value="validate">'."\n";
        foreach ($fields as $key=>$field) {
            echo '<div class="form-group row ' . ((!empty($field[6]))? 'has-error' : '') . '"' . ((substr($field[7], 0, 6) == 'hidden') ? (' ' . $field[7]) : '') . '>'."\n";
            echo '<label for="' . $field[2] . '" class="col-sm-' . $field[1] . ' control-label">' . $field[0] . '</label>'."\n";
            echo '<div class="col-sm-' . $field[3] . '">'."\n";
            if ($field[4] == 'textarea') {
               echo '<textarea name="body" class="form-control" rows="5" ' . $field[7] . '>' . $field[5] . '</textarea>'."\n";
            } else {    
               echo '<input type="' . $field[4] . '" class="form-control" name="' . $field[2] . '" value="' . $field[5] . '" ' . $field[7] . (($key == 0) ? ' autofocus' : '') . '>'."\n";
            }
            echo '<div class="help-block with-errors">' . $field[6] . '</div>'."\n";
            echo '</div>'."\n";
            echo '</div>'."\n";
        }
        echo '<div class="btn-toolbar" role="toolbar">'."\n";
        foreach ($buttons as $key=>$button) {
            echo '<button type = "' . $button[1] . '" class = "' . $button[2] . '" onclick = "document.location.href=\'' . $button[3] . '\';">' . $button[0] . '</button>'."\n";
        }
        echo '</div>'."\n";
        echo '     </form>'."\n";
        echo '    </div>'."\n";
        echo '   </div>'."\n";
        echo '  </div>'."\n";
        echo ' </div>'."\n";
        echo '</div>'."\n";

    }
    /*
     * $fields[]  = [['Имя', 'name',],...];
     * $buttons[] = ['Отмена', 'button', 'btn btn-default', './'];
     */
    public function bldTable($tytle, $tytleform, $width, $fields, $rows, $buttons, $count_entrys, $entry_on_page, $current_page, $max_pages_list) {
        $widthlr = (12 - $width) / 2;
        echo '<div class="container">' . "\n";
        echo ' <div class="row row-offcanvas row-offcanvas-center">' . "\n";
        echo ' <div class="col-xs-12 col-sm-12 col-md-12"><h3 align="center">' . $tytle . '</h3></div>' . "\n";
        echo ' <div class="col-xs-' . $widthlr . ' col-sm-' . $widthlr . ' col-md-' . $widthlr . '"></div>' . "\n";
        echo '  <div class="col-xs-' . $width . ' col-sm-' . $width . ' col-md-' . $width . '">' . "\n";
        echo '   <div class="panel panel-info">' . "\n";
        echo '    <div class="panel-heading">' . "\n";
        echo $tytleform . "\n";
        echo '    </div>' . "\n";
        echo '    <div class="panel-body">' . "\n";

        $this->bldPaginator($count_entrys, $entry_on_page, $current_page, $max_pages_list);

        echo '<table class="table table-striped table-bordered">' . "\n";
        echo '<thead>' . "\n";
        echo '<tr>' . "\n";
        foreach($fields as $key=>$field) {
            echo '<th>' . $field[0] . '</th>' . "\n";
        }
        echo '</tr>' . "\n";
        echo '</thead>' . "\n";
        echo '<tbody>' . "\n";
        foreach($rows as $key=>$row){
            echo '<tr>' . "\n";
            foreach($row as $k=>$r){
                echo '<td>' . $r . '</td>' . "\n";
            }
            echo '</tr>' . "\n";
        }
        echo '</tbody>' . "\n";

        echo '    </div>' . "\n";
        echo '   </div>' . "\n";
        echo '  </div>' . "\n";
        echo ' </div>' . "\n";
        echo '</div>' . "\n";
    }
    /*
     * $fields[]  = [['Имя', 'name',],...];
     * $buttons[] = ['Отмена', 'button', 'btn btn-default', './'];
     */

    public function bldPaginator($count_entrys, $entry_on_page, $current_page, $max_pages_list) {
        echo '<nav aria-label = "Page navigation">' . "\n";
        echo '<ul class = "pagination">' . "\n";
        echo '<li>' . "\n";
        echo '<a href = "' . './?current_page=' . '1' . '" aria-label = "Previous">' . "\n";
        echo '<span aria-hidden = "true">&laquo;' . "\n";
        echo '</span>' . "\n";
        echo '</a>' . "\n";
        echo '</li >' . "\n";
            
        $count_pages = ceil($count_entrys / $entry_on_page);
        $first_page = $current_page - (int) ($max_pages_list / 2);
        if ($first_page <= 1)
            $first_page = 1;
        else {
            if ($count_pages - $first_page < $max_pages_list) {
                $first_page = $count_pages - $max_pages_list + 1;
                if ($first_page <= 1)
                    $first_page = 1;
            }
        }
        $last_page = $first_page + $max_pages_list - 1;
        if ($last_page > $count_pages)
            $last_page = $count_pages;
/*
        if ($first_page != 1)
            echo '< |';
*/
        for ($i = $first_page; $i <= $last_page; $i++) {
            echo '<li ' . (($i == $current_page) ? 'class="active"' : '') . '><a href="' . './?current_page=' . $i . '">' . $i . '</a></li>' . "\n";
        }
/*
        if ($last_page < $count_pages)
            echo ' >';
 */
        echo '<li>' . "\n";
        echo '<a href = "' . './?current_page=' . $count_pages . '" aria-label = "Next">' . "\n";
        echo '<span aria-hidden = "true">&raquo;' . "\n";
        echo '</span>' . "\n";
        echo '</a>' . "\n";
        echo '</li>' . "\n";
        echo '</ul>' . "\n";
        echo '</nav>' . "\n";
    }
}
