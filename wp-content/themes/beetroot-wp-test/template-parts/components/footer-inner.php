<?php
$footer = get_field( 'footer', 'options' );

$navigation_columns = $footer['navigation_columns'];
$socials            = $footer['socials'];
?>

<footer id="footer" class="footer">
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

								<?php if ( ! empty( $column_title ) ): ?>

                                    <h6 class="footer__nav-title footer__nav-title--mobile"><?php echo $column_title ?></h6>

								<?php endif; ?>

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
            <div class="footer__col footer__col--socials">

				<?php if ( $socials ): ?>

                    <ul class="socials-list footer__socials-list">


						<?php foreach ( $socials as $item ):
							$socials_link = $item['link'];
							$socials_icon = $item['icon'];
							?>

                            <li class="socials-list__item">
                                <img class="socials-list__icon" <?php echo getImageAttributesById( $socials_icon['id'], 200 ); ?>>
                                <a href="<?php echo $socials_link['url'] ?>"
                                   target="<?php echo $socials_link['target'] ?>"></a>
                            </li>

						<?php endforeach; ?>

                    </ul>

				<?php endif; ?>

                <div class="subscribe footer__subscribe">
                    <h6 class="subscribe__title">subscribe to news</h6>
                    <form class="subscribe__form"
                          action="https://beetroot.us12.list-manage.com/subscribe/post-json?u=566efbac8b30750fe9c9d744a&amp;id=04557f958e&amp;c=callback"
                          method="post" target="_blank">
                        <input class="subscribe__form-input" type="email" name="EMAIL" placeholder="Enter Email Adress"
                               required="">
                        <input class="subscribe__form-submit" type="submit" value="">
                        <div class="subscribe__form-message subscribe__form-message--success">Thank you for
                            subscribing!
                        </div>
                        <div class="subscribe__form-message subscribe__form-message--error">Something went wrong</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</footer>