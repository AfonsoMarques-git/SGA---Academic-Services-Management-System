<nav class="navbar navbar-expand-lg" aria-label="Navegacao principal">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand" href="<?php echo url('dashboard.php'); ?>" aria-label="Voltar ao dashboard">
            <span class="fw-bold">SGA</span>
            <span class="d-none d-sm-inline"> | Sistema de Gestao Academica</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item dropdown">
                    <button class="btn btn-link nav-link dropdown-toggle" id="languageDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="<?php echo t('nav.language'); ?>">
                        <?php echo strtoupper(getCurrentLanguage()); ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item <?php echo getCurrentLanguage() === 'pt' ? 'active' : ''; ?>" href="<?php echo url('change-language.php?lang=pt'); ?>"><?php echo t('language.portuguese'); ?></a></li>
                        <li><a class="dropdown-item <?php echo getCurrentLanguage() === 'en' ? 'active' : ''; ?>" href="<?php echo url('change-language.php?lang=en'); ?>"><?php echo t('language.english'); ?></a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <button
                        type="button"
                        class="theme-toggle js-theme-toggle"
                        aria-label="<?php echo t('nav.toggle_theme') . ' ' . t('nav.dark'); ?>"
                        aria-pressed="false"
                        data-label-light="<?php echo t('nav.theme_light'); ?>"
                        data-label-dark="<?php echo t('nav.theme_dark'); ?>"
                        data-next-light="<?php echo t('nav.light'); ?>"
                        data-next-dark="<?php echo t('nav.dark'); ?>"
                        data-toggle-prefix="<?php echo t('nav.toggle_theme'); ?>"
                    >
                        <?php echo t('nav.theme'); ?>
                    </button>
                </li>
                <?php if (hasAnyRole('aluno', 'funcionario')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('messages.php'); ?>"><?php echo t('nav.my_requests'); ?></a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <span class="nav-link" aria-label="<?php echo t('nav.user_logged_in'); ?>">
                        <?php echo h($_SESSION['full_name']); ?>
                        <small class="text-warning">(<?php echo h(getRoleLabel()); ?>)</small>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('logout.php'); ?>"><?php echo t('nav.logout'); ?></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
