<?php

return [
    // Installation prompts
    'install_title' => 'Instalar Aplicativo',
    'install_description' => 'Obtenha uma melhor experiência com o aplicativo instalado',
    'install_button' => 'Instalar',
    'dismiss_button' => 'Dispensar',

    // iOS installation
    'ios_install_title' => 'Instalar Aplicativo no iOS',
    'ios_install_description' => 'Para instalar este aplicativo no iOS:',
    'ios_step_1' => 'Toque no botão Compartilhar',
    'ios_step_2' => 'Selecione "Adicionar à Tela de Início"',
    'ios_step_3' => 'Toque em "Adicionar" para confirmar',
    'got_it' => 'Entendi',

    // Updates
    'update_available' => 'Atualização Disponível',
    'update_description' => 'Uma nova versão do aplicativo está disponível',
    'update_now' => 'Atualizar Agora',
    'update_later' => 'Mais Tarde',

    // Offline page
    'offline_title' => 'Você está Offline',
    'offline_subtitle' => 'Parece que você perdeu sua conexão com a internet. Não se preocupe, você ainda pode acessar alguns recursos do painel administrativo.',
    'offline_status' => 'Offline',
    'online_status' => 'Online',
    'offline_indicator' => 'Sem conexão com a internet',

    // Features
    'available_features' => 'Recursos Disponíveis',
    'feature_cached_pages' => 'Acessar páginas e dados em cache',
    'feature_offline_forms' => 'Preencher formulários (sincronizará quando online)',
    'feature_local_storage' => 'Visualizar informações armazenadas localmente',
    'feature_auto_sync' => 'Sincronização automática quando a conexão retornar',

    // Actions
    'retry_connection' => 'Tentar Novamente',
    'go_home' => 'Ir para Início',

    // Validation messages
    'validation' => [
        'manifest_missing' => 'Manifesto da aplicação web não encontrado',
        'service_worker_missing' => 'Service worker não encontrado',
        'icons_missing' => 'Ícones PWA obrigatórios não encontrados',
        'https_required' => 'HTTPS é obrigatório para PWA em produção',
    ],

    // Setup command messages
    'setup' => [
        'starting' => 'Configurando Plugin Filament PWA...',
        'publishing_assets' => 'Publicando recursos PWA...',
        'generating_icons' => 'Gerando ícones PWA...',
        'validating' => 'Validando configuração PWA...',
        'completed' => 'Configuração do Filament PWA concluída!',
        'assets_published' => 'Recursos publicados com sucesso!',
        'icons_generated' => 'Ícones gerados com sucesso!',
        'validation_passed' => 'Todos os recursos PWA estão presentes!',
        'validation_failed' => 'Validação PWA falhou:',
        'source_not_found' => 'Imagem fonte não encontrada',
        'provide_source' => 'Por favor, forneça uma imagem fonte usando --source=path/to/image.svg ou --source=path/to/image.png',
        'svg_detected' => 'Fonte SVG detectada - usando conversão vetorial de alta qualidade',
        'raster_detected' => 'Imagem raster detectada - usando processamento de imagens',
        'imagick_unavailable' => 'Extensão Imagick não disponível. Recorrendo ao GD com conversão SVG.',
        'intervention_unavailable' => 'Intervention Image não instalado. Usando fallback GD.',
        'gd_fallback' => 'Usando fallback GD para processamento de imagens (qualidade limitada)',
        'install_imagick' => 'Por favor, instale a extensão Imagick ou Intervention Image para melhor qualidade.',
    ],
];
