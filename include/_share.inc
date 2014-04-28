<?php
    /**
    * Zubr
    * Like and share block
    * To use it properly 
    *   '$current_university_id' variable is required
    * @package Zubr
    * @author Dauren Sarsenov 
    * @version 1.0, 01.09.2010
    * @since engine v.1.0
    * @copyright (c) 2010+ by Dauren Sarsenov
    */
?>
<div class="section share-section">
    <div class="section-title">
        <table class="nopadding nomargin">
            <tr class="nopadding nomargin">
                 <td class="nopadding nomargin"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="www.zubbr.kz">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
                </td>
                <td class="nopadding nomargin"><iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fzubbr.kz%2Funi%2F<?php echo University::find_by_id($current_university_id)->alias?>%2F&amp;layout=standard&amp;show_faces=true&amp;width=300&amp;action=recommend&amp;font=arial&amp;colorscheme=light&amp;height=10" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:24px;" allowTransparency="true"></iframe>
                </td>
                <td class="nopadding nomargin">
                <div class="toolbar nopadding nomargin" style="width: 150px;">
                        <a href="/uni/like/<?php echo University::find_by_id($current_university_id)->alias?>/" title="Добавить <?php echo University::find_by_id($current_university_id)->short_name?> в профайл"><img src="/images/icon-plus.gif" width="20" height="20" alt="" /> я хочу тут учиться</a>    
                </div>
                </td>
               
                
            </tr>
        </table>
        </div>
</div>