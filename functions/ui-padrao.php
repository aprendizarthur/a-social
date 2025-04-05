<?php 
//ARQUIVO COM FUNÇÕES PARA INJETAR NO HTML UI PADRÃO 
function barraFerramentas(){
    echo '
    <header>
        <nav id="nav-ferramentas">
            <ul>
                <li class="img-avatar">
                    <a class="mt-2 mb-4" href="perfil.php?id=' . $_SESSION['id_usuario'] . '">
                        <img class="avatar d-flex" src="' . $_SESSION['avatar_usuario'] . '" alt="Avatar do usuário">
                        <h2 class="mt-2 nome-usuario ubuntu-bold d-none d-lg-inline-block">'. $_SESSION['nome_usuario'] .' </h2>
                    </a>
                </li> 
                <li>
                    <a class="my-2" href="home.php"><i class="fa-solid fa-house fa-lg px-2" style="color: #272727;"></i><h2 class="ubuntu-regular d-none d-lg-inline-block"> Home</h2></a>
                </li> 
                <li class="d-none d-lg-block">
                    <a class="my-2" href="explorar.php"><i class="fa-solid fa-magnifying-glass fa-lg fa-lg px-2" style="color: #272727;"></i><h2 class="ubuntu-regular d-none d-lg-inline-block"> Explorar</h2></a>
                </li>
                <li>
                    <a class="d-lg-none my-2" href="explorar.php"><i class="fa-solid fa-magnifying-glass fa-lg" style="color: #272727;"></i></a>
                </li>
                <li>
                    <a class="my-2" href="perfil.php?id=' . $_SESSION['id_usuario'] . '"><i class="fa-regular fa-user fa-xl px-2" style="color: #272727;"></i><h2 class="ubuntu-regular d-none d-lg-inline-block"> Perfil</h2></a>
                </li>
                <li>
                    <a class="d-lg-none my-2" href="home.php#nova-postagem"><i class="fa-solid fa-plus fa-lg" style="color: #272727;"></i></a>
                </li>
                <li>
                    <a class="my-2" href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket fa-lg px-2" style="color: #272727;"></i><h2 class="ubuntu-regular d-none d-lg-inline-block"> Sair</h2></a>
                </li>
                <li>
                    <a class="d-none d-lg-block mt-3 mb-2 btn btn-primary w-100" href="home.php#nova-postagem"><span class="d-none d-lg-block ubuntu-bold d-none d-lg-inline-block">Postar</span></a>
                </li>
            </ul>
        </nav>
    </header>
    ';
}

?>