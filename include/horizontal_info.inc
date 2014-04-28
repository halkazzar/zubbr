<?php
    $news = News::find_recent(3);
    
    if(!empty($news)):
?>

				<div class="col3 left">
					<div class="column-content">
						<div class="post">
							<?php 
                             
                            $content = str_replace('images/', '/images/', $news[0]->avatar);
                            $content = str_replace('<img ', '<img class="bordered left" ', $content);
                            echo $content;
                            ?><h4><a href="/news/<?php echo $news[0]->news_id?>/"><?php echo $news[0]->title;?></a></h4>
                            
						</div>
					</div>
				</div>

				<div class="col3 col3-mid left">
					<div class="column-content">

						<div class="post">
							<?php
                             
                            $content = str_replace('images/', '/images/', $news[1]->avatar);
                            $content = str_replace('<img ', '<img class="bordered left" ', $content);
                            echo $content;
                            ?><h4><a href="/news/<?php echo $news[1]->news_id?>/"><?php echo $news[1]->title;?></a></h4>
                            
						</div>

					</div>
				</div>

				<div class="col3 right">
					<div class="column-content">

						<div class="post">
							<?php
                              
                            $content = str_replace('images/', '/images/', $news[2]->avatar);
                            $content = str_replace('<img ', '<img class="bordered left" ', $content);
                            echo $content;
                            ?><h4><a href="/news/<?php echo $news[2]->news_id?>/"><?php echo $news[2]->title;?></a></h4>
						</div>

					</div>
				</div>
<?php endif?>