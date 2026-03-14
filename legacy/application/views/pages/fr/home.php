<h1>Gestion des congés et des heures supplémentaires</h1>

<p>Bienvenue dans Jorani. N'hésitez pas à cliquer sur l'icône d'aide en ligne de chacun des écrans (<i class="mdi mdi-help-circle-outline"></i>). Cela vous donnera accès à la documentation en Français de la fonctionnalité que vous êtes en train d'utiliser.</p>

<h4>Pour les employés</h4>

<p>Si vous êtes un employé, vous pourriez maintenant :</p>

<ul>
    <li>Voir vos <a href="<?php echo base_url();?>leaves/counters">compteurs de congés</a>.</li>
    <li>Voir la <a href="<?php echo base_url();?>leaves">liste des demandes de congés que vous avez soumises</a>.</li>
    <li>Soumettre une <a href="<?php echo base_url();?>leaves/create">nouvelle demande</a>.</li>
</ul>

<h4>Pour les managers</h4>

<p>Si vous êtes le responsable hiérarchique d'au moins un employé, vous pourriez maintenant :</p>

<ul>
    <li>Valider <a href="<?php echo base_url();?>requests">les demandes de congés qui vous ont été soumises</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Valider <a href="<?php echo base_url();?>overtime">les déclarations d'heures supplémentaires qui vous ont été soumises</a>.</li>
    <?php } ?>
</ul>

<h4>Pour les responsables RH</h4>

<p>Le <a href="https://fr.jorani.org/" target="_blank">site officiel de Jorani</a> contient la documentation complète et en Français du système, par exemple :</p>

<ul>
    <li><a href="https://fr.jorani.org/utilisation/prise-en-main.html" target="_blank">Un tutoriel de prise en main</a>.</li>
    <li><a href="https://fr.jorani.org/utilisation/guide-de-demarrage-rapide.html" target="_blank">Le guide de ce qu'il faut configurer</a> pour commencer.</li>
</ul>
