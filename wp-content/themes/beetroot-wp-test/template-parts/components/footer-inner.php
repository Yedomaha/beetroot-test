<?php
$footer = get_field( 'footer', 'options' );

$navigation_columns = $footer['navigation_columns'];
$socials            = $footer['socials'];
?>

<footer class="footer">
    <div class="container">
        <div class="footer__inner">

            <div class="footer__col footer__col--nav">
                <div class="footer__nav-header">

					<?php if ( $navigation_columns ):
						foreach ( $navigation_columns as $column ):
							$column_title = $column['column_title'];
							$column_nav = $column['column_nav'];
							?>

                            <div class="footer__nav-col">
                                <h6 class="footer__nav-title"><?php echo $column_title ?></h6>
                            </div>

						<?php endforeach;
					endif; ?>

                </div>
                <div class="footer__nav-body">

					<?php if ( $navigation_columns ):
						foreach ( $navigation_columns as $column ):
							$column_title = $column['column_title'];
							$column_nav = $column['column_nav'];
							?>

                            <div class="footer__nav-col">
                                <h6 class="footer__nav-title footer__nav-title--mobile"><?php echo $column_title ?></h6>

								<?php if ( $column_nav ): ?>

                                    <nav class="footer__nav">
                                        <ul>

											<?php foreach ( $column_nav as $item ):
												$link = $item['link'];
												?>

                                                <li>
                                                    <a href="<?php echo $link['url'] ?>"
                                                       target="<?php echo $link['target'] ?>"><?php echo $link['title'] ?></a>
                                                </li>

											<?php endforeach; ?>

                                        </ul>
                                    </nav>

								<?php endif; ?>

                            </div>

						<?php endforeach;
					endif; ?>

                </div>
            </div>

        </div>
    </div>
</footer>