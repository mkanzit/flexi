<?php /* Template Name: Entreprise */ ?>
<?php get_header('2'); ?>

<div id="primary" class="content-area enterprise">
	<main id="main" class="site-main" role="main">
    <div class="container normal-content">

      <section id="portrait" class="block-page-section block-spaced">
        <div class="container">
          <h1 class="section-title"><?php _e('Portrait', 'flexiauto') ?></h1>
          <div class="row">
            <?php the_field('portrait');  ?>
          </div>
        </div>
      </section>

      <section id="nos-valeurs" class="block-page-section block-spaced">
        <div class="container">
          <h1 class="section-title"><?php _e('Nos valeurs', 'flexiauto') ?></h1>
          <div class="row">
            <?php the_field('nos_valeurs');  ?>
          </div>
        </div>
      </section>

      <section id="notre-valeur-ajoutee" class="block-page-section block-spaced">
        <div class="container">
          <h1 class="section-title"><?php _e('Notre valeur ajoutée', 'flexiauto') ?></h1>
          <div class="row">
            <?php the_field('notre_valeur_ajoutee');  ?>
          </div>
        </div>
      </section>

      <section id="qualite" class="block-page-section block-spaced">
        <div class="container">
          <h1 class="section-title"><?php _e('Qualité', 'flexiauto') ?></h1>
          <div class="row">
            <?php the_field('qualite');  ?>
          </div>
        </div>
      </section>

      <section id="environnement" class="block-page-section block-spaced">
        <div class="container">
          <h1 class="section-title"><?php _e('Environnement', 'flexiauto') ?></h1>
          <div class="row">
            <?php the_field('environnement');  ?>
          </div>
        </div>
      </section>

      <section id="disponibilite-directe" class="block-page-section block-spaced">
        <div class="container">
          <h1 class="section-title"><?php _e('Disponibilite directe', 'flexiauto') ?></h1>
          <div class="row">
            <?php the_field('disponibilite_directe');  ?>
          </div>
        </div>
      </section>

      <section id="sav-centres-de-reparations" class="block-page-section block-spaced">
        <div class="container">
          <h1 class="section-title"><?php _e('Sav - Centres de reparations', 'flexiauto') ?></h1>
          <div class="row">
            <?php the_field('sav_centres_de_reparations');  ?>
          </div>
        </div>
      </section>

    </div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
