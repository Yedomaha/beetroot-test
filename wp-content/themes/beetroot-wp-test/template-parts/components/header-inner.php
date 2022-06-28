<?php
$header = get_field( 'header', 'options' );

$header_logo = $header['logo'];
$header_nav  = $header['navigation'];
?>

<header class="header">
    <div class="container">
        <div class="header__inner">

			<?php if ( ! empty( $header_logo ) ): ?>

                <div class="header__logo">
                    <img <?php echo getImageAttributesById( $header_logo['id'], 300 ); ?>>
                    <a href="<?php echo get_home_url() ?>"></a>
                </div>

			<?php endif; ?>

            <div data-lang-switch class="header__lang-switcher">
                <div class="header__current-lang"><button>Eng</button></div>
                <ul>
                    <li><a href="">Укр</a></li>
                    <li><a href="">Рус</a></li>
                </ul>
            </div>

			<?php if ( ! empty( $header_nav ) ): ?>

                <nav data-burger-nav class="header__nav-wrap">
                    <ul>

						<?php foreach ( $header_nav as $item ):
							$item_link = $item['link'];
							$item_modifier = $item['special_item_mode'] ? 'default-cta' : false;
							?>

                            <li>
                                <a href="<?php echo $item_link['url'] ?>"
                                   target="<?php echo $item_link['target'] ?>"
                                   class="<?php echo $item_modifier ?>"><?php echo $item_link['title'] ?></a>
                            </li>

						<?php endforeach; ?>

                    </ul>
                </nav>

			<?php endif; ?>

            <div class="header__burger-btn">
                <div data-burger-btn class="burger-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

        </div>
    </div>
</header>