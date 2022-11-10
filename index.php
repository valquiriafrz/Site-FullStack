<?php

/**
 * Importa as configurações do site:
 * Referências:
 *  • https://www.w3schools.com/php/php_includes.asp
 **/
require('includes/config.php');

// Se usuário já está logado...
if (isset($_COOKIE[$c['ucookie']]))

  // Extrai os dados do usuário:
  $user = json_decode($_COOKIE[$c['ucookie']], true);

/**
 * Obtém e filtra o nome da página da URL:
 * Referências:
 *  • https://www.w3schools.com/jsref/jsref_trim_string.asp
 *  • https://www.php.net/manual/en/function.urldecode.php
 *  • https://www.w3schools.com/php/func_string_htmlentities.asp
 *  • https://www.w3schools.com/php/php_superglobals.asp
 *  • https://www.w3schools.com/php/php_superglobals_server.asp
 **/
$route = trim(htmlentities($_SERVER['QUERY_STRING']));

// Se não solicitou uma rota, usa a rota da página inicial:
if ($route == '') $route = 'home';

// Remove coisas depois da "/" caso exista:
$route = explode('/', $route)[0];

/**
 * Monta todos os caminhos dos arquivos da página em uma coleção:
 * Referências:
 *  • https://www.w3schools.com/php/php_arrays.asp
 *  • https://www.w3schools.com/php/func_array.asp
 **/
$page = array(
  'php' => "pages/{$route}/index.php",
  'css' => "pages/{$route}/index.css",
  'js' => "pages/{$route}/index.js",
);

if (!file_exists($page['php'])) :

  $page = array(
    'php' => "pages/404/index.php",
    'css' => "pages/404/index.css",
    'js' => "pages/404/index.js",
  );
endif;

require($page['php']);

if (file_exists($page['css']))
  
  $page_css = "<link rel=\"stylesheet\" href=\"/{$page['css']}\">";

if (file_exists($page['js']))
  
  $page_js = "<script src=\"/{$page['js']}\"></script>";

if ($page_title == '')
 
  $title = "{$c['sitename']} {$c['titlesep']} {$c['siteslogan']}";
else
  
  $title = "{$c['sitename']} {$c['titlesep']} {$page_title}";

$fsocial = '<nav>
  <h4>Redes sociais:</h4>';

for ($i = 0; $i < count($s); $i++) : 
  $fsocial .= <<<HTML
    
<a href="{$s[$i]['link']}" target="_blank" title="Acesse nosso {$s[$i]['name']}">
  <i class="fa-brands {$s[$i]['icon']} fa-fw"></i>
  <span>{$s[$i]['name']}</span>
</a>
HTML;

endfor;

$fsocial .= '
</nav>';

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" href="<?php echo $c['sitefavicon'] ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/style.css" />
  <?php
  echo $page_css;
  ?>
  <title><?php echo $title ?></title>
</head>

<body>
  <a id="top"></a>
  <div id="wrap">

    <header>
      <a href="/" title="Página inicial">
        <?php echo $c['sitelogo'] ?>
      </a>
      <h1>
        <?php echo $c['sitename'] ?>
        <small><?php echo $c['siteslogan'] ?></small>
      </h1>
    </header>

    <nav>
      <a href="/" title="Página inicial">
        <span>Início</span>
      </a>
      <a href="/?contacts" title="Faça contato" class="dropable">
        <span>Contatos</span>
      </a>
      <a href="/?about" title="Sobre a gente" class="dropable">
        <span>Sobre</span>
      </a>

      <?php

      if (isset($user['uid'])) :

      ?>

        <a href="/?profile" title="Perfil de <?php echo $user['name'] ?>" class="dropable profile">
          <img src="<?php echo $user['photo'] ?>" alt="Perfil de <?php echo $user['name'] ?>">
          <span>Perfil</span>
        </a>

      <?php

      else :

      ?>

        <a href="/?login" title="Login de usuário" class="dropable">
          <i class="fa-solid fa-right-to-bracket fa-fw"></i>
          <span>Login</span>
        </a>

      <?php

      endif;

      ?>

      <a href="/?menu" id="btnMenu" title="Abre/fecha menu">
        <i class="fa-solid fa-ellipsis-vertical fa-fw"></i>
      </a>
    </nav>

    <div id="dropable">
      <nav>
        <?php if (isset($user['uid'])) : ?>
          <a href="/?profile" title="Perfil de <?php echo $user['name'] ?>" class="profile">
            <img src="<?php echo $user['photo'] ?>" alt="Perfil de <?php echo $user['name'] ?>">
            <span>Perfil</span>
          </a>
        <?php else : ?>
          <a href="/?login" title="Login de usuário">
            <i class="fa-solid fa-right-to-bracket fa-fw"></i>
            <span>Login</span>
          </a>
        <?php endif; ?>
        <hr>
        <a href="/?search" title="Procurar no site"><i class="fa-solid fa-magnifying-glass fa-fw"></i><span>Procurar</span></a>
        <hr>
        <a href="/?contacts" title="Faça contato"><i class="fa-solid fa-comments fa-fw"></i><span>Contatos</span></a>
        <a href="/?site" title="Sobre o site..."><i class="fa-solid fa-globe fa-fw"></i><span>Sobre o site</span></a>
        <a href="/?policies" title="Políticas de Privacidade"><i class="fa-solid fa-user-lock fa-fw"></i><span>Sua privacidade</span></a>
      </nav>
    </div>

    <main id="content">
      <?php
      echo $page_content;
      ?>
    </main>

    <footer>

      <div id="fsup">
        <a href="/" title="Página inicial">
          <i class="fa-solid fa-house-chimney fa-fw"></i>
        </a>
        <div id="copy">&copy; 2022 <?php echo $c['sitename'] ?></div>
        <a href="#top" title="Topo da página">
          <i class="fa-solid fa-circle-up fa-fw"></i>
        </a>
      </div>

      <div id="finf">
        <?php
        echo $fsocial;
        ?>
        <nav>
          <h4>Acesso rápido:</h4>
          <a href="/?contacts">
            <i class="fa-solid fa-comments fa-fw"></i>
            <span>Contatos</span>
          </a>
          <a href="/?about">
            <i class="fa-solid fa-circle-info fa-fw"></i>
            <span>Sobre</span>
          </a>
          <a href="/?policies">
            <i class="fa-solid fa-user-lock fa-fw"></i>
            <span>Sua privacidade</span>
          </a>
        </nav>
      </div>
    </footer>
    <span>&nbsp;</span>

  </div>

  <div id="acCookies">
    <div class="cookieBody">
      <div class="cookieBox">
        <div>
          Usamos cookies para lhe fornecer uma experiência de navegação melhor e mais segura.
          Não se preocupe, todos os seus dados pessoais estão protegidos.
        </div>
        <button id="accept">Entendi!</button>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="/script.js"></script>
  <?php
  
  echo $page_js;
  ?>
</body>

</html>