<?php $__env->startSection('before_footer'); ?>
    <link rel="stylesheet" href="/css/larchik/editor.min.css"/>
    <link rel="stylesheet" href="/css/larchik/dashicons.css">
    <link rel="stylesheet" href="/css/larchik/media-views.css">
    <link rel="stylesheet" href="/css/larchik/media.css">
    <link rel="stylesheet" href="/css/larchik/list-tables.css">
    <link rel="stylesheet" href="/css/larchik/buttons.css">
    <link rel="stylesheet" href="/css/larchik/filter.css">
    <script>
        addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
        var ajaxurl = '/admin/ajax',
            pagenow = 'upload',
            typenow = 'attachment',
            adminpage = 'upload-php',
            thousandsSeparator = '&nbsp;',
            decimalPoint = ',',
            isRtl = 0;
    </script>

    <script src='/js/larchik/utils.js'></script>
    <script src='/js/larchik/plupload/plupload.full.min.js'></script>
    <script>
        var commonL10n = <?php echo $commonL10n; ?>;
        var _wpUtilSettings = <?php echo $wpUtilSettings; ?>;
        var _wpMediaModelsL10n = <?php echo $wpMediaModelsL10n; ?>;
        var pluploadL10n = <?php echo $pluploadL10n; ?>;
        var _wpPluploadSettings = {"defaults":{"file_data_name":"async-upload","url":"\/admin\/async-upload","resize":{"width":3840,"height":1920},"filters":{"max_file_size":"<?php echo e($max_size); ?>b","mime_types":[{"extensions":"svg,jpg,jpeg,jpe,gif,png,bmp,tiff,tif,ico,asf,asx,wmv,wmx,wm,avi,divx,flv,mov,qt,mpeg,mpg,mpe,mp4,m4v,ogv,webm,mkv,3gp,3gpp,3g2,3gp2,txt,asc,c,cc,h,srt,csv,tsv,ics,rtx,css,htm,html,vtt,dfxp,mp3,m4a,m4b,aac,ra,ram,wav,ogg,oga,flac,mid,midi,wma,wax,mka,rtf,js,pdf,class,tar,zip,gz,gzip,rar,7z,psd,xcf,doc,pot,pps,ppt,wri,xla,xls,xlt,xlw,mdb,mpp,docx,docm,dotx,dotm,xlsx,xlsm,xlsb,xltx,xltm,xlam,pptx,pptm,ppsx,ppsm,potx,potm,ppam,sldx,sldm,onetoc,onetoc2,onetmp,onepkg,oxps,xps,odt,odp,ods,odg,odc,odb,odf,wp,wpd,key,numbers,pages"}]},"multipart_params":{"action":"upload-attachment","_wpnonce":"0cb09df612"}},"browser":{"mobile":false,"supported":true},"limitExceeded":false};
        var wpApiSettings = {"root":"\/wp-json\/","nonce":"848b553740","versionString":"wp\/v2\/"};
        var _wpMediaViewsL10n = <?php echo $_wpMediaViewsL10n; ?>;
        var mceViewL10n = <?php echo $mceViewL10n; ?>;
        var imageEditL10n = <?php echo $imageEditL10n; ?>;
        var _wpMediaGridSettings = <?php echo json_encode([
            'adminUrl' => '/admin/',
            'queryVars' => isset($query_vars) ? $query_vars : []
        ]); ?>;
        var attachMediaBoxL10n = <?php echo $attachMediaBoxL10n; ?>;
        var heartbeatSettings = {"nonce":"e980ac531b"};
        var authcheckL10n = <?php echo $authcheckL10n; ?>;
        var wpColorPickerL10n = <?php echo $wpColorPickerL10n; ?>;
        var quicktagsL10n = <?php echo $quicktagsL10n; ?>;
        var wpLinkL10n = <?php echo $wpLinkL10n; ?>;
        var uiAutocompleteL10n = <?php echo $uiAutocompleteL10n; ?>;
        var thickboxL10n = <?php echo $thickboxL10n; ?>;/* ]]> */
    </script>

    <script type="text/html" id="tmpl-media-frame">
        <div class="media-frame-menu"></div>
        <div class="media-frame-title"></div>
        <div class="media-frame-router"></div>
        <div class="media-frame-content"></div>
        <div class="media-frame-toolbar"></div>
        <div class="media-frame-uploader"></div>
    </script>

    <script type="text/html" id="tmpl-media-modal">
        <div tabindex="0" class="media-modal wp-core-ui">
            <button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Закрыть окно параметров файла</span></span></button>
            <div class="media-modal-content"></div>
        </div>
        <div class="media-modal-backdrop"></div>
    </script>

    <script type="text/html" id="tmpl-uploader-window">
        <div class="uploader-window-content">
            <h1>Перетащите файлы сюда</h1>
        </div>
    </script>

    <script type="text/html" id="tmpl-uploader-editor">
        <div class="uploader-editor-content">
            <div class="uploader-editor-title">Перетащите файлы сюда</div>
        </div>
    </script>

    <script type="text/html" id="tmpl-uploader-inline">
        <# var messageClass = data.message ? 'has-upload-message' : 'no-upload-message'; #>
        <# if ( data.canClose ) { #>
        <button class="close dashicons dashicons-no"><span class="screen-reader-text">Закрыть окно загрузчика</span></button>
        <# } #>
        <div class="uploader-inline-content <?php echo e(messageClass); ?>">
            <# if ( data.message ) { #>
            <h2 class="upload-message"><?php echo e(data.message); ?></h2>
            <# } #>
            <div class="upload-ui">
                <h2 class="upload-instructions drop-instructions">Перетащите файлы сюда</h2>
                <p class="upload-instructions drop-instructions">или</p>
                <button type="button" class="browser button button-hero">Выберите файлы</button>
            </div>

            <div class="upload-inline-status"></div>

            <div class="post-upload-ui">

                <p class="max-upload-size">Максимальный размер файла: <?php echo e($max_size); ?>.</p>

                <# if ( data.suggestedWidth && data.suggestedHeight ) { #>
                <p class="suggested-dimensions">
                    Предлагаемый размер изображения: <?php echo e(data.suggestedWidth); ?> на <?php echo e(data.suggestedHeight); ?> пикселов.					</p>
                <# } #>

            </div>
        </div>
    </script>

    <script type="text/html" id="tmpl-media-library-view-switcher">
        
        
        
        <a href="/admin/media?mode=grid" class="view-grid current">
            <span class="screen-reader-text">В виде сетки</span>
        </a>
    </script>

    <script type="text/html" id="tmpl-uploader-status">
        <h2>Загрузка</h2>
        <button type="button" class="button-link upload-dismiss-errors"><span class="screen-reader-text">Скрыть ошибки</span></button>

        <div class="media-progress-bar"><div></div></div>
        <div class="upload-details">
			<span class="upload-count">
				<span class="upload-index"></span> / <span class="upload-total"></span>
			</span>
            <span class="upload-detail-separator">&ndash;</span>
            <span class="upload-filename"></span>
        </div>
        <div class="upload-errors"></div>
    </script>

    <script type="text/html" id="tmpl-uploader-status-error">
        <span class="upload-error-filename"><?php echo e(data.filename); ?></span>
        <span class="upload-error-message"><?php echo e(data.message); ?></span>
    </script>

    <script type="text/html" id="tmpl-edit-attachment-frame">
        <div class="edit-media-header">
            <button class="left dashicons <# if ( ! data.hasPrevious ) { #> disabled <# } #>"><span class="screen-reader-text">Редактировать предыдущий файл</span></button>
            <button class="right dashicons <# if ( ! data.hasNext ) { #> disabled <# } #>"><span class="screen-reader-text">Редактировать следующий файл</span></button>
        </div>
        <div class="media-frame-title"></div>
        <div class="media-frame-content"></div>
    </script>

    <script type="text/html" id="tmpl-attachment-details-two-column">
        <div class="attachment-media-view <?php echo e(data.orientation); ?>">
            <div class="thumbnail thumbnail-<?php echo e(data.type); ?>">
                <# if ( data.uploading ) { #>
                <div class="media-progress-bar"><div></div></div>
                <# } else if ( data.sizes && data.sizes.large ) { #>
                <img class="details-image" src="<?php echo e(data.sizes.large.url); ?>" draggable="false" alt="" />
                <# } else if ( data.sizes && data.sizes.full ) { #>
                <img class="details-image" src="<?php echo e(data.sizes.full.url); ?>" draggable="false" alt="" />
                <# } else if ( -1 === jQuery.inArray( data.type, [ 'audio', 'video' ] ) ) { #>
                <img class="details-image icon" src="<?php echo e(data.icon); ?>" draggable="false" alt="" />
                <# } #>

                <# if ( 'audio' === data.type ) { #>
                <div class="wp-media-wrapper">
                    <audio style="visibility: hidden" controls class="wp-audio-shortcode" width="100%" preload="none">
                        <source type="<?php echo e(data.mime); ?>" src="<?php echo e(data.url); ?>"/>
                    </audio>
                </div>
                <# } else if ( 'video' === data.type ) {
                var w_rule = '';
                if ( data.width ) {
                w_rule = 'width: ' + data.width + 'px;';
                } else if ( wp.media.view.settings.contentWidth ) {
                w_rule = 'width: ' + wp.media.view.settings.contentWidth + 'px;';
                }
                #>
                <div style="<?php echo e(w_rule); ?>" class="wp-media-wrapper wp-video">
                    <video controls="controls" class="wp-video-shortcode" preload="metadata"
                    <# if ( data.width ) { #>width="<?php echo e(data.width); ?>"<# } #>
                    <# if ( data.height ) { #>height="<?php echo e(data.height); ?>"<# } #>
                    <# if ( data.image && data.image.src !== data.icon ) { #>poster="<?php echo e(data.image.src); ?>"<# } #>>
                    <source type="<?php echo e(data.mime); ?>" src="<?php echo e(data.url); ?>"/>
                    </video>
                </div>
                <# } #>

                <div class="attachment-actions">
                    <# if ( 'image' === data.type && ! data.uploading && data.sizes && data.can.save ) { #>
                    
                    <# } else if ( 'pdf' === data.subtype && data.sizes ) { #>
                    Предпросмотр документа					<# } #>
                </div>
            </div>
        </div>
        <div class="attachment-info">
			<span class="settings-save-status">
				<span class="spinner"></span>
				<span class="saved">Сохранено.</span>
			</span>
            <div class="details">
                <div class="filename"><strong>Имя файла:</strong> <?php echo e(data.filename); ?></div>
                <div class="filename"><strong>Тип файла:</strong> <?php echo e(data.mime); ?></div>
                <div class="uploaded"><strong>Загружен:</strong> <?php echo e(data.dateFormatted); ?></div>

                <div class="file-size"><strong>Размер файла:</strong> <?php echo e(data.filesizeHumanReadable); ?></div>
                <# if ( 'image' === data.type && ! data.uploading ) { #>
                <# if ( data.width && data.height ) { #>
                <div class="dimensions"><strong>Размеры:</strong> <?php echo e(data.width); ?> &times; <?php echo e(data.height); ?></div>
                <# } #>
                <# } #>

                <# if ( data.fileLength ) { #>
                <div class="file-length"><strong>Продолжительность:</strong> <?php echo e(data.fileLength); ?></div>
                <# } #>

                <# if ( 'audio' === data.type && data.meta.bitrate ) { #>
                <div class="bitrate">
                    <strong>Битрейт:</strong> <?php echo e(Math.round( data.meta.bitrate / 1000 )); ?>kb/s
                    <# if ( data.meta.bitrate_mode ) { #>
                    <?php echo e(' ' + data.meta.bitrate_mode.toUpperCase()); ?>

                    <# } #>
                </div>
                <# } #>

                <div class="compat-meta">
                    <# if ( data.compat && data.compat.meta ) { #>
                    <?php echo e(data.compat.meta); ?>

                    <# } #>
                </div>
            </div>

            <div class="settings">
                <label class="setting" data-setting="url">
                    <span class="name">URL</span>
                    <input type="text" value="<?php echo e(data.url); ?>" readonly />
                </label>
                <# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
                <label class="setting" data-setting="title">
                    <span class="name">Заголовок</span>
                    <input type="text" value="<?php echo e(data.title); ?>" <?php echo e(maybeReadOnly); ?> />
                </label>
                <# if ( 'audio' === data.type ) { #>
                <label class="setting" data-setting="artist">
                    <span class="name">Исполнитель</span>
                    <input type="text" value="<?php echo e(data.artist || data.meta.artist || ''); ?>" />
                </label>
                <label class="setting" data-setting="album">
                    <span class="name">Альбом</span>
                    <input type="text" value="<?php echo e(data.album || data.meta.album || ''); ?>" />
                </label>
                <# } #>
                <label class="setting" data-setting="caption">
                    <span class="name">Подпись</span>
                    <textarea <?php echo e(maybeReadOnly); ?>><?php echo e(data.caption); ?></textarea>
                </label>
                <# if ( 'image' === data.type ) { #>
                <label class="setting" data-setting="alt">
                    <span class="name">Атрибут alt</span>
                    <input type="text" value="<?php echo e(data.alt); ?>" <?php echo e(maybeReadOnly); ?> />
                </label>
                <# } #>
                <label class="setting" data-setting="description">
                    <span class="name">Описание</span>
                    <textarea <?php echo e(maybeReadOnly); ?>><?php echo e(data.description); ?></textarea>
                </label>
                <label class="setting">
                    <span class="name">Пользователь</span>
                    <span class="value"><?php echo e(data.authorName); ?></span>
                </label>
                <# if ( data.uploadedToTitle ) { #>
                <label class="setting">
                    <span class="name">Загружен для</span>
                    <# if ( data.uploadedToLink ) { #>
                    <span class="value"><a href="<?php echo e(data.uploadedToLink); ?>"><?php echo e(data.uploadedToTitle); ?></a></span>
                    <# } else { #>
                    <span class="value"><?php echo e(data.uploadedToTitle); ?></span>
                    <# } #>
                </label>
                <# } #>
                <div class="attachment-compat"></div>
            </div>

            <div class="actions">
                <a class="view-attachment" href="<?php echo e(data.link); ?>" target="_blank">Просмотреть страницу вложения</a>
                
                
                
                <# if ( ! data.uploading && data.can.remove ) { #> |
                <button type="button" class="button-link delete-attachment">Удалить навсегда</button>
                <# } #>
            </div>

        </div>
    </script>

    <script type="text/html" id="tmpl-attachment">
        <div class="attachment-preview js--select-attachment type-<?php echo e(data.type); ?> subtype-<?php echo e(data.subtype); ?> <?php echo e(data.orientation); ?>">
            <div class="thumbnail">
                <# if ( data.uploading ) { #>
                <div class="media-progress-bar"><div style="width: <?php echo e(data.percent); ?>%"></div></div>
                <# } else if ( 'image' === data.type && data.sizes ) { #>
                <div class="centered">
                    <img src="<?php echo e(data.size.url); ?>" draggable="false" alt="" />
                </div>
                <# } else { #>
                <div class="centered">
                    <# if ( data.image && data.image.src && data.image.src !== data.icon ) { #>
                    <img src="<?php echo e(data.image.src); ?>" class="thumbnail" draggable="false" alt="" />
                    <# } else if ( data.sizes && data.sizes.medium ) { #>
                    <img src="<?php echo e(data.sizes.medium.url); ?>" class="thumbnail" draggable="false" alt="" />
                    <# } else { #>
                    <img src="<?php echo e(data.icon); ?>" class="icon" draggable="false" alt="" />
                    <# } #>
                </div>
                <div class="filename">
                    <div><?php echo e(data.filename); ?></div>
                </div>
                <# } #>
            </div>
            <# if ( data.buttons.close ) { #>
            <button type="button" class="button-link attachment-close media-modal-icon"><span class="screen-reader-text">Удалить</span></button>
            <# } #>
        </div>
        <# if ( data.buttons.check ) { #>
        <button type="button" class="check" tabindex="-1"><span class="media-modal-icon"></span><span class="screen-reader-text">Снять выделение</span></button>
        <# } #>
        <#
        var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly';
        if ( data.describe ) {
        if ( 'image' === data.type ) { #>
        <input type="text" value="<?php echo e(data.caption); ?>" class="describe" data-setting="caption"
               placeholder="Подпишите это изображение&hellip;" <?php echo e(maybeReadOnly); ?> />
        <# } else { #>
        <input type="text" value="<?php echo e(data.title); ?>" class="describe" data-setting="title"
        <# if ( 'video' === data.type ) { #>
        placeholder="Опишите этот видеофайл&hellip;"
        <# } else if ( 'audio' === data.type ) { #>
        placeholder="Опишите этот аудиофайл&hellip;"
        <# } else { #>
        placeholder="Опишите этот медиафайл&hellip;"
        <# } #> <?php echo e(maybeReadOnly); ?> />
        <# }
        } #>
    </script>

    <script type="text/html" id="tmpl-attachment-details">
        <h2>
            Параметры файла			<span class="settings-save-status">
				<span class="spinner"></span>
				<span class="saved">Сохранено.</span>
			</span>
        </h2>
        <div class="attachment-info">
            <div class="thumbnail thumbnail-<?php echo e(data.type); ?>">
                <# if ( data.uploading ) { #>
                <div class="media-progress-bar"><div></div></div>
                <# } else if ( 'image' === data.type && data.sizes ) { #>
                <img src="<?php echo e(data.size.url); ?>" draggable="false" alt="" />
                <# } else { #>
                <img src="<?php echo e(data.icon); ?>" class="icon" draggable="false" alt="" />
                <# } #>
            </div>
            <div class="details">
                <div class="filename"><?php echo e(data.filename); ?></div>
                <div class="uploaded"><?php echo e(data.dateFormatted); ?></div>

                <div class="file-size"><?php echo e(data.filesizeHumanReadable); ?></div>
                <# if ( 'image' === data.type && ! data.uploading ) { #>
                <# if ( data.width && data.height ) { #>
                <div class="dimensions"><?php echo e(data.width); ?> &times; <?php echo e(data.height); ?></div>
                <# } #>

                <# if ( data.can.save && data.sizes ) { #>
                <a class="edit-attachment" href="<?php echo e(data.editLink); ?>&amp;image-editor" target="_blank">Редактировать</a>
                <# } #>
                <# } #>

                <# if ( data.fileLength ) { #>
                <div class="file-length">Продолжительность: <?php echo e(data.fileLength); ?></div>
                <# } #>

                <# if ( ! data.uploading && data.can.remove ) { #>
                <button type="button" class="button-link delete-attachment">Удалить навсегда</button>
                <# } #>

                <div class="compat-meta">
                    <# if ( data.compat && data.compat.meta ) { #>
                    <?php echo e(data.compat.meta); ?>

                    <# } #>
                </div>
            </div>
        </div>

        <label class="setting" data-setting="url">
            <span class="name">URL</span>
            <input type="text" value="<?php echo e(data.url); ?>" readonly />
        </label>
        <# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
        <label class="setting" data-setting="title">
            <span class="name">Заголовок</span>
            <input type="text" value="<?php echo e(data.title); ?>" <?php echo e(maybeReadOnly); ?> />
        </label>
        <# if ( 'audio' === data.type ) { #>
        <label class="setting" data-setting="artist">
            <span class="name">Исполнитель</span>
            <input type="text" value="<?php echo e(data.artist || data.meta.artist || ''); ?>" />
        </label>
        <label class="setting" data-setting="album">
            <span class="name">Альбом</span>
            <input type="text" value="<?php echo e(data.album || data.meta.album || ''); ?>" />
        </label>
        <# } #>
        <label class="setting" data-setting="caption">
            <span class="name">Подпись</span>
            <textarea <?php echo e(maybeReadOnly); ?>><?php echo e(data.caption); ?></textarea>
        </label>
        <# if ( 'image' === data.type ) { #>
        <label class="setting" data-setting="alt">
            <span class="name">Атрибут alt</span>
            <input type="text" value="<?php echo e(data.alt); ?>" <?php echo e(maybeReadOnly); ?> />
        </label>
        <# } #>
        <label class="setting" data-setting="description">
            <span class="name">Описание</span>
            <textarea <?php echo e(maybeReadOnly); ?>><?php echo e(data.description); ?></textarea>
        </label>
    </script>

    <script type="text/html" id="tmpl-media-selection">
        <div class="selection-info">
            <span class="count"></span>
            <# if ( data.editable ) { #>
            <button type="button" class="button-link edit-selection">Изменить выбор</button>
            <# } #>
            <# if ( data.clearable ) { #>
            <button type="button" class="button-link clear-selection">Сброс</button>
            <# } #>
        </div>
        <div class="selection-view"></div>
    </script>

    <script type="text/html" id="tmpl-attachment-display-settings">
        <h2>Настройки отображения файла</h2>

        <# if ( 'image' === data.type ) { #>
        <label class="setting align">
            <span>Выравнивание</span>
            <select class="alignment"
                    data-setting="align"
            <# if ( data.userSettings ) { #>
            data-user-setting="align"
            <# } #>>

            <option value="left">
                Слева					</option>
            <option value="center">
                По центру					</option>
            <option value="right">
                Справа					</option>
            <option value="none" selected>
                Нет					</option>
            </select>
        </label>
        <# } #>

        <div class="setting">
            <label>
                <# if ( data.model.canEmbed ) { #>
                <span>Вставить объект или ссылку</span>
                <# } else { #>
                <span>Ссылка</span>
                <# } #>

                <select class="link-to"
                        data-setting="link"
                <# if ( data.userSettings && ! data.model.canEmbed ) { #>
                data-user-setting="urlbutton"
                <# } #>>

                <# if ( data.model.canEmbed ) { #>
                <option value="embed" selected>
                    Вставить медиаплеер					</option>
                <option value="file">
                    <# } else { #>
                <option value="none" selected>
                    Нет					</option>
                <option value="file">
                    <# } #>
                    <# if ( data.model.canEmbed ) { #>
                    Ссылка на медиафайл					<# } else { #>
                    Медиафайл					<# } #>
                </option>
                <option value="post">
                    <# if ( data.model.canEmbed ) { #>
                    Ссылка на страницу вложения					<# } else { #>
                    Страница вложения					<# } #>
                </option>
                <# if ( 'image' === data.type ) { #>
                <option value="custom">
                    Произвольный URL					</option>
                <# } #>
                </select>
            </label>
            <input type="text" class="link-to-custom" data-setting="linkUrl" />
        </div>

        <# if ( 'undefined' !== typeof data.sizes ) { #>
        <label class="setting">
            <span>Размер</span>
            <select class="size" name="size"
                    data-setting="size"
            <# if ( data.userSettings ) { #>
            data-user-setting="imgsize"
            <# } #>>
            <#
            var size = data.sizes['thumbnail'];
            if ( size ) { #>
            <option value="thumbnail" >
                Миниатюра &ndash; <?php echo e(size.width); ?> &times; <?php echo e(size.height); ?>

            </option>
            <# } #>
            <#
            var size = data.sizes['medium'];
            if ( size ) { #>
            <option value="medium" >
                Средний &ndash; <?php echo e(size.width); ?> &times; <?php echo e(size.height); ?>

            </option>
            <# } #>
            <#
            var size = data.sizes['large'];
            if ( size ) { #>
            <option value="large" >
                Большой &ndash; <?php echo e(size.width); ?> &times; <?php echo e(size.height); ?>

            </option>
            <# } #>
            <#
            var size = data.sizes['full'];
            if ( size ) { #>
            <option value="full"  selected='selected'>
                Полный &ndash; <?php echo e(size.width); ?> &times; <?php echo e(size.height); ?>

            </option>
            <# } #>
            </select>
        </label>
        <# } #>
    </script>

    <script type="text/html" id="tmpl-gallery-settings">
        <h2>Настройки галереи</h2>

        <label class="setting">
            <span>Ссылка</span>
            <select class="link-to"
                    data-setting="link"
            <# if ( data.userSettings ) { #>
            data-user-setting="urlbutton"
            <# } #>>

            <option value="post" <# if ( ! wp.media.galleryDefaults.link || 'post' == wp.media.galleryDefaults.link ) {
            #>selected="selected"<# }
            #>>
            Страница вложения				</option>
            <option value="file" <# if ( 'file' == wp.media.galleryDefaults.link ) { #>selected="selected"<# } #>>
            Медиафайл				</option>
            <option value="none" <# if ( 'none' == wp.media.galleryDefaults.link ) { #>selected="selected"<# } #>>
            Нет				</option>
            </select>
        </label>

        <label class="setting">
            <span>Колонки</span>
            <select class="columns" name="columns"
                    data-setting="columns">
                <option value="1" <#
                if ( 1 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
                #>>
                1					</option>
                <option value="2" <#
                if ( 2 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
                #>>
                2					</option>
                <option value="3" <#
                if ( 3 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
                #>>
                3					</option>
                <option value="4" <#
                if ( 4 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
                #>>
                4					</option>
                <option value="5" <#
                if ( 5 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
                #>>
                5					</option>
                <option value="6" <#
                if ( 6 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
                #>>
                6					</option>
                <option value="7" <#
                if ( 7 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
                #>>
                7					</option>
                <option value="8" <#
                if ( 8 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
                #>>
                8					</option>
                <option value="9" <#
                if ( 9 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
                #>>
                9					</option>
            </select>
        </label>

        <label class="setting">
            <span>Случайный порядок</span>
            <input type="checkbox" data-setting="_orderbyRandom" />
        </label>

        <label class="setting size">
            <span>Размер</span>
            <select class="size" name="size"
                    data-setting="size"
            <# if ( data.userSettings ) { #>
            data-user-setting="imgsize"
            <# } #>
            >
            <option value="thumbnail">
                Миниатюра					</option>
            <option value="medium">
                Средний					</option>
            <option value="large">
                Большой					</option>
            <option value="full">
                Полный					</option>
            </select>
        </label>
    </script>

    <script type="text/html" id="tmpl-playlist-settings">
        <h2>Настройки плей-листа</h2>

        <# var emptyModel = _.isEmpty( data.model ),
        isVideo = 'video' === data.controller.get('library').props.get('type'); #>

        <label class="setting">
            <input type="checkbox" data-setting="tracklist" <# if ( emptyModel ) { #>
            checked="checked"
            <# } #> />
            <# if ( isVideo ) { #>
            <span>Показывать список видеофайлов</span>
            <# } else { #>
            <span>Показывать список треков</span>
            <# } #>
        </label>

        <# if ( ! isVideo ) { #>
        <label class="setting">
            <input type="checkbox" data-setting="artists" <# if ( emptyModel ) { #>
            checked="checked"
            <# } #> />
            <span>Показывать имя исполнителя</span>
        </label>
        <# } #>

        <label class="setting">
            <input type="checkbox" data-setting="images" <# if ( emptyModel ) { #>
            checked="checked"
            <# } #> />
            <span>Показывать изображения</span>
        </label>
    </script>

    <script type="text/html" id="tmpl-embed-link-settings">
        <label class="setting link-text">
            <span>Текст ссылки</span>
            <input type="text" class="alignment" data-setting="linkText" />
        </label>
        <div class="embed-container" style="display: none;">
            <div class="embed-preview"></div>
        </div>
    </script>

    <script type="text/html" id="tmpl-embed-image-settings">
        <div class="thumbnail">
            <img src="<?php echo e(data.model.url); ?>" draggable="false" alt="" />
        </div>

        <label class="setting caption">
            <span>Подпись</span>
            <textarea data-setting="caption" />
        </label>

        <label class="setting alt-text">
            <span>Атрибут alt</span>
            <input type="text" data-setting="alt" />
        </label>

        <div class="setting align">
            <span>Расположение</span>
            <div class="button-group button-large" data-setting="align">
                <button class="button" value="left">
                    Слева				</button>
                <button class="button" value="center">
                    По центру				</button>
                <button class="button" value="right">
                    Справа				</button>
                <button class="button active" value="none">
                    Нет				</button>
            </div>
        </div>

        <div class="setting link-to">
            <span>Ссылка</span>
            <div class="button-group button-large" data-setting="link">
                <button class="button" value="file">
                    Адрес (URL)				</button>
                <button class="button" value="custom">
                    Произвольный URL				</button>
                <button class="button active" value="none">
                    Нет				</button>
            </div>
            <input type="text" class="link-to-custom" data-setting="linkUrl" />
        </div>
    </script>

    <script type="text/html" id="tmpl-image-details">
        <div class="media-embed">
            <div class="embed-media-settings">
                <div class="column-image">
                    <div class="image">
                        <img src="<?php echo e(data.model.url); ?>" draggable="false" alt="" />

                        <# if ( data.attachment && window.imageEdit ) { #>
                        <div class="actions">
                            <input type="button" class="edit-attachment button" value="Редактировать оригинал" />
                            <input type="button" class="replace-attachment button" value="Заменить" />
                        </div>
                        <# } #>
                    </div>
                </div>
                <div class="column-settings">
                    <label class="setting caption">
                        <span>Подпись</span>
                        <textarea data-setting="caption"><?php echo e(data.model.caption); ?></textarea>
                    </label>

                    <label class="setting alt-text">
                        <span>Атрибут alt</span>
                        <input type="text" data-setting="alt" value="<?php echo e(data.model.alt); ?>" />
                    </label>

                    <h2>Настройки отображения</h2>
                    <div class="setting align">
                        <span>Расположение</span>
                        <div class="button-group button-large" data-setting="align">
                            <button class="button" value="left">
                                Слева							</button>
                            <button class="button" value="center">
                                По центру							</button>
                            <button class="button" value="right">
                                Справа							</button>
                            <button class="button active" value="none">
                                Нет							</button>
                        </div>
                    </div>

                    <# if ( data.attachment ) { #>
                    <# if ( 'undefined' !== typeof data.attachment.sizes ) { #>
                    <label class="setting size">
                        <span>Размер</span>
                        <select class="size" name="size"
                                data-setting="size"
                        <# if ( data.userSettings ) { #>
                        data-user-setting="imgsize"
                        <# } #>>
                        <#
                        var size = data.sizes['thumbnail'];
                        if ( size ) { #>
                        <option value="thumbnail">
                            Миниатюра &ndash; <?php echo e(size.width); ?> &times; <?php echo e(size.height); ?>

                        </option>
                        <# } #>
                        <#
                        var size = data.sizes['medium'];
                        if ( size ) { #>
                        <option value="medium">
                            Средний &ndash; <?php echo e(size.width); ?> &times; <?php echo e(size.height); ?>

                        </option>
                        <# } #>
                        <#
                        var size = data.sizes['large'];
                        if ( size ) { #>
                        <option value="large">
                            Большой &ndash; <?php echo e(size.width); ?> &times; <?php echo e(size.height); ?>

                        </option>
                        <# } #>
                        <#
                        var size = data.sizes['full'];
                        if ( size ) { #>
                        <option value="full">
                            Полный &ndash; <?php echo e(size.width); ?> &times; <?php echo e(size.height); ?>

                        </option>
                        <# } #>
                        <option value="custom">
                            Произвольный									</option>
                        </select>
                    </label>
                    <# } #>
                    <div class="custom-size<# if ( data.model.size !== 'custom' ) { #> hidden<# } #>">
                        <label><span>Ширина <small>(px)</small></span> <input data-setting="customWidth" type="number" step="1" value="<?php echo e(data.model.customWidth); ?>" /></label><span class="sep">&times;</span><label><span>Высота <small>(px)</small></span><input data-setting="customHeight" type="number" step="1" value="<?php echo e(data.model.customHeight); ?>" /></label>
                    </div>
                    <# } #>

                    <div class="setting link-to">
                        <span>Ссылка</span>
                        <select data-setting="link">
                            <# if ( data.attachment ) { #>
                            <option value="file">
                                Медиафайл							</option>
                            <option value="post">
                                Страница вложения							</option>
                            <# } else { #>
                            <option value="file">
                                Адрес (URL)							</option>
                            <# } #>
                            <option value="custom">
                                Произвольный URL							</option>
                            <option value="none">
                                Нет							</option>
                        </select>
                        <input type="text" class="link-to-custom" data-setting="linkUrl" />
                    </div>
                    <div class="advanced-section">
                        <h2><button type="button" class="button-link advanced-toggle">Дополнительные настройки</button></h2>
                        <div class="advanced-settings hidden">
                            <div class="advanced-image">
                                <label class="setting title-text">
                                    <span>Атрибут title</span>
                                    <input type="text" data-setting="title" value="<?php echo e(data.model.title); ?>" />
                                </label>
                                <label class="setting extra-classes">
                                    <span>CSS-класс изображения</span>
                                    <input type="text" data-setting="extraClasses" value="<?php echo e(data.model.extraClasses); ?>" />
                                </label>
                            </div>
                            <div class="advanced-link">
                                <div class="setting link-target">
                                    <label><input type="checkbox" data-setting="linkTargetBlank" value="_blank" <# if ( data.model.linkTargetBlank ) { #>checked="checked"<# } #>>Открывать в новой вкладке</label>
                                </div>
                                <label class="setting link-rel">
                                    <span>Отношение</span>
                                    <input type="text" data-setting="linkRel" value="<?php echo e(data.model.linkRel); ?>" />
                                </label>
                                <label class="setting link-class-name">
                                    <span>CSS-класс ссылки</span>
                                    <input type="text" data-setting="linkClassName" value="<?php echo e(data.model.linkClassName); ?>" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/html" id="tmpl-image-editor">
        <div id="media-head-<?php echo e(data.id); ?>"></div>
        <div id="image-editor-<?php echo e(data.id); ?>"></div>
    </script>

    <script type="text/html" id="tmpl-audio-details">
        <# var ext, html5types = {
        mp3: wp.media.view.settings.embedMimes.mp3,
        ogg: wp.media.view.settings.embedMimes.ogg
        }; #>

        <div class="media-embed media-embed-details">
            <div class="embed-media-settings embed-audio-settings">
                <audio style="visibility: hidden"
                       controls
                       class="wp-audio-shortcode"
                       width="<?php echo e(_.isUndefined( data.model.width ) ? 400 : data.model.width); ?>"
                       preload="<?php echo e(_.isUndefined( data.model.preload ) ? 'none' : data.model.preload); ?>"
                <#
                if ( ! _.isUndefined( data.model.autoplay ) && data.model.autoplay ) {
                #> autoplay<#
                }
                if ( ! _.isUndefined( data.model.loop ) && data.model.loop ) {
                #> loop<#
                }
                #>
                >
                <# if ( ! _.isEmpty( data.model.src ) ) { #>
                <source src="<?php echo e(data.model.src); ?>" type="<?php echo e(wp.media.view.settings.embedMimes[ data.model.src.split('.').pop() ]); ?>" />
                <# } #>

                <# if ( ! _.isEmpty( data.model.mp3 ) ) { #>
                <source src="<?php echo e(data.model.mp3); ?>" type="<?php echo e(wp.media.view.settings.embedMimes[ 'mp3' ]); ?>" />
                <# } #>
                <# if ( ! _.isEmpty( data.model.ogg ) ) { #>
                <source src="<?php echo e(data.model.ogg); ?>" type="<?php echo e(wp.media.view.settings.embedMimes[ 'ogg' ]); ?>" />
                <# } #>
                <# if ( ! _.isEmpty( data.model.flac ) ) { #>
                <source src="<?php echo e(data.model.flac); ?>" type="<?php echo e(wp.media.view.settings.embedMimes[ 'flac' ]); ?>" />
                <# } #>
                <# if ( ! _.isEmpty( data.model.m4a ) ) { #>
                <source src="<?php echo e(data.model.m4a); ?>" type="<?php echo e(wp.media.view.settings.embedMimes[ 'm4a' ]); ?>" />
                <# } #>
                <# if ( ! _.isEmpty( data.model.wav ) ) { #>
                <source src="<?php echo e(data.model.wav); ?>" type="<?php echo e(wp.media.view.settings.embedMimes[ 'wav' ]); ?>" />
                <# } #>
                </audio>

                <# if ( ! _.isEmpty( data.model.src ) ) {
                ext = data.model.src.split('.').pop();
                if ( html5types[ ext ] ) {
                delete html5types[ ext ];
                }
                #>
                <label class="setting">
                    <span>SRC</span>
                    <input type="text" disabled="disabled" data-setting="src" value="<?php echo e(data.model.src); ?>" />
                    <button type="button" class="button-link remove-setting">Удалить источник аудио</button>
                </label>
                <# } #>
                <# if ( ! _.isEmpty( data.model.mp3 ) ) {
                if ( ! _.isUndefined( html5types.mp3 ) ) {
                delete html5types.mp3;
                }
                #>
                <label class="setting">
                    <span>MP3</span>
                    <input type="text" disabled="disabled" data-setting="mp3" value="<?php echo e(data.model.mp3); ?>" />
                    <button type="button" class="button-link remove-setting">Удалить источник аудио</button>
                </label>
                <# } #>
                <# if ( ! _.isEmpty( data.model.ogg ) ) {
                if ( ! _.isUndefined( html5types.ogg ) ) {
                delete html5types.ogg;
                }
                #>
                <label class="setting">
                    <span>OGG</span>
                    <input type="text" disabled="disabled" data-setting="ogg" value="<?php echo e(data.model.ogg); ?>" />
                    <button type="button" class="button-link remove-setting">Удалить источник аудио</button>
                </label>
                <# } #>
                <# if ( ! _.isEmpty( data.model.flac ) ) {
                if ( ! _.isUndefined( html5types.flac ) ) {
                delete html5types.flac;
                }
                #>
                <label class="setting">
                    <span>FLAC</span>
                    <input type="text" disabled="disabled" data-setting="flac" value="<?php echo e(data.model.flac); ?>" />
                    <button type="button" class="button-link remove-setting">Удалить источник аудио</button>
                </label>
                <# } #>
                <# if ( ! _.isEmpty( data.model.m4a ) ) {
                if ( ! _.isUndefined( html5types.m4a ) ) {
                delete html5types.m4a;
                }
                #>
                <label class="setting">
                    <span>M4A</span>
                    <input type="text" disabled="disabled" data-setting="m4a" value="<?php echo e(data.model.m4a); ?>" />
                    <button type="button" class="button-link remove-setting">Удалить источник аудио</button>
                </label>
                <# } #>
                <# if ( ! _.isEmpty( data.model.wav ) ) {
                if ( ! _.isUndefined( html5types.wav ) ) {
                delete html5types.wav;
                }
                #>
                <label class="setting">
                    <span>WAV</span>
                    <input type="text" disabled="disabled" data-setting="wav" value="<?php echo e(data.model.wav); ?>" />
                    <button type="button" class="button-link remove-setting">Удалить источник аудио</button>
                </label>
                <# } #>

                <# if ( ! _.isEmpty( html5types ) ) { #>
                <div class="setting">
                    <span>Добавьте дополнительные источники для максимальной совместимости с HTML5:</span>
                    <div class="button-large">
                        <# _.each( html5types, function (mime, type) { #>
                        <button class="button add-media-source" data-mime="<?php echo e(mime); ?>"><?php echo e(type); ?></button>
                        <# } ) #>
                    </div>
                </div>
                <# } #>

                <div class="setting preload">
                    <span>Предварительная загрузка</span>
                    <div class="button-group button-large" data-setting="preload">
                        <button class="button" value="auto">Авто</button>
                        <button class="button" value="metadata">Метаданные</button>
                        <button class="button active" value="none">Нет</button>
                    </div>
                </div>

                <label class="setting checkbox-setting autoplay">
                    <input type="checkbox" data-setting="autoplay" />
                    <span>Автозапуск</span>
                </label>

                <label class="setting checkbox-setting">
                    <input type="checkbox" data-setting="loop" />
                    <span>Зациклить</span>
                </label>
            </div>
        </div>
    </script>

    <script type="text/html" id="tmpl-video-details">
        <# var ext, html5types = {
        mp4: wp.media.view.settings.embedMimes.mp4,
        ogv: wp.media.view.settings.embedMimes.ogv,
        webm: wp.media.view.settings.embedMimes.webm
        }; #>

        <div class="media-embed media-embed-details">
            <div class="embed-media-settings embed-video-settings">
                <div class="wp-video-holder">
                    <#
                    var w = ! data.model.width || data.model.width > 640 ? 640 : data.model.width,
                    h = ! data.model.height ? 360 : data.model.height;

                    if ( data.model.width && w !== data.model.width ) {
                    h = Math.ceil( ( h * w ) / data.model.width );
                    }
                    #>

                    <#  var w_rule = '', classes = [],
                    w, h, settings = wp.media.view.settings,
                    isYouTube = isVimeo = false;

                    if ( ! _.isEmpty( data.model.src ) ) {
                    isYouTube = data.model.src.match(/youtube|youtu\.be/);
                    isVimeo = -1 !== data.model.src.indexOf('vimeo');
                    }

                    if ( settings.contentWidth && data.model.width >= settings.contentWidth ) {
                    w = settings.contentWidth;
                    } else {
                    w = data.model.width;
                    }

                    if ( w !== data.model.width ) {
                    h = Math.ceil( ( data.model.height * w ) / data.model.width );
                    } else {
                    h = data.model.height;
                    }

                    if ( w ) {
                    w_rule = 'width: ' + w + 'px; ';
                    }

                    if ( isYouTube ) {
                    classes.push( 'youtube-video' );
                    }

                    if ( isVimeo ) {
                    classes.push( 'vimeo-video' );
                    }

                    #>
                    <div style="<?php echo e(w_rule); ?>" class="wp-video">
                        <video controls
                               class="wp-video-shortcode <?php echo e(classes.join( ' ' )); ?>"
                        <# if ( w ) { #>width="<?php echo e(w); ?>"<# } #>
                        <# if ( h ) { #>height="<?php echo e(h); ?>"<# } #>
                        <#
                        if ( ! _.isUndefined( data.model.poster ) && data.model.poster ) {
                        #> poster="<?php echo e(data.model.poster); ?>"<#
                        } #>
                        preload="<?php echo e(_.isUndefined( data.model.preload ) ? 'metadata' : data.model.preload); ?>"<#
                        if ( ! _.isUndefined( data.model.autoplay ) && data.model.autoplay ) {
                        #> autoplay<#
                        }
                        if ( ! _.isUndefined( data.model.loop ) && data.model.loop ) {
                        #> loop<#
                        }
                        #>
                        >
                        <# if ( ! _.isEmpty( data.model.src ) ) {
                        if ( isYouTube ) { #>
                        <source src="<?php echo e(data.model.src); ?>" type="video/youtube" />
                        <# } else if ( isVimeo ) { #>
                        <source src="<?php echo e(data.model.src); ?>" type="video/vimeo" />
                        <# } else { #>
                        <source src="<?php echo e(data.model.src); ?>" type="<?php echo e(settings.embedMimes[ data.model.src.split('.').pop() ]); ?>" />
                        <# }
                        } #>

                        <# if ( data.model.mp4 ) { #>
                        <source src="<?php echo e(data.model.mp4); ?>" type="<?php echo e(settings.embedMimes[ 'mp4' ]); ?>" />
                        <# } #>
                        <# if ( data.model.m4v ) { #>
                        <source src="<?php echo e(data.model.m4v); ?>" type="<?php echo e(settings.embedMimes[ 'm4v' ]); ?>" />
                        <# } #>
                        <# if ( data.model.webm ) { #>
                        <source src="<?php echo e(data.model.webm); ?>" type="<?php echo e(settings.embedMimes[ 'webm' ]); ?>" />
                        <# } #>
                        <# if ( data.model.ogv ) { #>
                        <source src="<?php echo e(data.model.ogv); ?>" type="<?php echo e(settings.embedMimes[ 'ogv' ]); ?>" />
                        <# } #>
                        <# if ( data.model.flv ) { #>
                        <source src="<?php echo e(data.model.flv); ?>" type="<?php echo e(settings.embedMimes[ 'flv' ]); ?>" />
                        <# } #>
                        <?php echo e(data.model.content); ?>

                        </video>
                    </div>

                    <# if ( ! _.isEmpty( data.model.src ) ) {
                    ext = data.model.src.split('.').pop();
                    if ( html5types[ ext ] ) {
                    delete html5types[ ext ];
                    }
                    #>
                    <label class="setting">
                        <span>SRC</span>
                        <input type="text" disabled="disabled" data-setting="src" value="<?php echo e(data.model.src); ?>" />
                        <button type="button" class="button-link remove-setting">Удалить источник видео</button>
                    </label>
                    <# } #>
                    <# if ( ! _.isEmpty( data.model.mp4 ) ) {
                    if ( ! _.isUndefined( html5types.mp4 ) ) {
                    delete html5types.mp4;
                    }
                    #>
                    <label class="setting">
                        <span>MP4</span>
                        <input type="text" disabled="disabled" data-setting="mp4" value="<?php echo e(data.model.mp4); ?>" />
                        <button type="button" class="button-link remove-setting">Удалить источник видео</button>
                    </label>
                    <# } #>
                    <# if ( ! _.isEmpty( data.model.m4v ) ) {
                    if ( ! _.isUndefined( html5types.m4v ) ) {
                    delete html5types.m4v;
                    }
                    #>
                    <label class="setting">
                        <span>M4V</span>
                        <input type="text" disabled="disabled" data-setting="m4v" value="<?php echo e(data.model.m4v); ?>" />
                        <button type="button" class="button-link remove-setting">Удалить источник видео</button>
                    </label>
                    <# } #>
                    <# if ( ! _.isEmpty( data.model.webm ) ) {
                    if ( ! _.isUndefined( html5types.webm ) ) {
                    delete html5types.webm;
                    }
                    #>
                    <label class="setting">
                        <span>WEBM</span>
                        <input type="text" disabled="disabled" data-setting="webm" value="<?php echo e(data.model.webm); ?>" />
                        <button type="button" class="button-link remove-setting">Удалить источник видео</button>
                    </label>
                    <# } #>
                    <# if ( ! _.isEmpty( data.model.ogv ) ) {
                    if ( ! _.isUndefined( html5types.ogv ) ) {
                    delete html5types.ogv;
                    }
                    #>
                    <label class="setting">
                        <span>OGV</span>
                        <input type="text" disabled="disabled" data-setting="ogv" value="<?php echo e(data.model.ogv); ?>" />
                        <button type="button" class="button-link remove-setting">Удалить источник видео</button>
                    </label>
                    <# } #>
                    <# if ( ! _.isEmpty( data.model.flv ) ) {
                    if ( ! _.isUndefined( html5types.flv ) ) {
                    delete html5types.flv;
                    }
                    #>
                    <label class="setting">
                        <span>FLV</span>
                        <input type="text" disabled="disabled" data-setting="flv" value="<?php echo e(data.model.flv); ?>" />
                        <button type="button" class="button-link remove-setting">Удалить источник видео</button>
                    </label>
                    <# } #>
                </div>

                <# if ( ! _.isEmpty( html5types ) ) { #>
                <div class="setting">
                    <span>Добавьте дополнительные источники для максимальной совместимости с HTML5:</span>
                    <div class="button-large">
                        <# _.each( html5types, function (mime, type) { #>
                        <button class="button add-media-source" data-mime="<?php echo e(mime); ?>"><?php echo e(type); ?></button>
                        <# } ) #>
                    </div>
                </div>
                <# } #>

                <# if ( ! _.isEmpty( data.model.poster ) ) { #>
                <label class="setting">
                    <span>Постер</span>
                    <input type="text" disabled="disabled" data-setting="poster" value="<?php echo e(data.model.poster); ?>" />
                    <button type="button" class="button-link remove-setting">Удалить постер</button>
                </label>
                <# } #>
                <div class="setting preload">
                    <span>Предварительная загрузка</span>
                    <div class="button-group button-large" data-setting="preload">
                        <button class="button" value="auto">Авто</button>
                        <button class="button" value="metadata">Метаданные</button>
                        <button class="button active" value="none">Нет</button>
                    </div>
                </div>

                <label class="setting checkbox-setting autoplay">
                    <input type="checkbox" data-setting="autoplay" />
                    <span>Автозапуск</span>
                </label>

                <label class="setting checkbox-setting">
                    <input type="checkbox" data-setting="loop" />
                    <span>Зациклить</span>
                </label>

                <label class="setting" data-setting="content">
                    <span>Треки (субтитры, подписи, описания, главы или метаданные)</span>
                    <#
                    var content = '';
                    if ( ! _.isEmpty( data.model.content ) ) {
                    var tracks = jQuery( data.model.content ).filter( 'track' );
                    _.each( tracks.toArray(), function (track) {
                    content += track.outerHTML; #>
                    <p>
                        <input class="content-track" type="text" value="<?php echo e(track.outerHTML); ?>" />
                        <button type="button" class="button-link remove-setting remove-track">Удалить дорожку видео</button>
                    </p>
                    <# } ); #>
                    <# } else { #>
                    <em>Субтитры не указаны.</em>
                    <# } #>
                    <textarea class="hidden content-setting"><?php echo e(content); ?></textarea>
                </label>
            </div>
        </div>
    </script>

    <script type="text/html" id="tmpl-editor-gallery">
        <# if ( data.attachments.length ) { #>
        <div class="gallery gallery-columns-<?php echo e(data.columns); ?>">
            <# _.each( data.attachments, function( attachment, index ) { #>
            <dl class="gallery-item">
                <dt class="gallery-icon">
                    <# if ( attachment.thumbnail ) { #>
                    <img src="<?php echo e(attachment.thumbnail.url); ?>" width="<?php echo e(attachment.thumbnail.width); ?>" height="<?php echo e(attachment.thumbnail.height); ?>" alt="" />
                    <# } else { #>
                    <img src="<?php echo e(attachment.url); ?>" alt="" />
                    <# } #>
                </dt>
                <# if ( attachment.caption ) { #>
                <dd class="wp-caption-text gallery-caption">
                    <?php echo e(data.verifyHTML( attachment.caption )); ?>

                </dd>
                <# } #>
            </dl>
            <# if ( index % data.columns === data.columns - 1 ) { #>
            <br style="clear: both;">
            <# } #>
            <# } ); #>
        </div>
        <# } else { #>
        <div class="wpview-error">
            <div class="dashicons dashicons-format-gallery"></div><p>Элементов не найдено.</p>
        </div>
        <# } #>
    </script>

    <script type="text/html" id="tmpl-crop-content">
        <img class="crop-image" src="<?php echo e(data.url); ?>" alt="Область просмотра и обрезки изображения. Требует управления мышью.">
        <div class="upload-errors"></div>
    </script>

    <script type="text/html" id="tmpl-site-icon-preview">
        <h2>Просмотреть</h2>
        <strong aria-hidden="true">Как иконка в браузере</strong>
        <div class="favicon-preview">
            <img src="/images/larchik/browser.png" class="browser-preview" width="182" height="" alt="" />

            <div class="favicon">
                <img id="preview-favicon" src="<?php echo e(data.url); ?>" alt="Просмотреть как иконку в браузере"/>
            </div>
            <span class="browser-title" aria-hidden="true">ПригонАвто</span>
        </div>

        <strong aria-hidden="true">Как иконка приложения</strong>
        <div class="app-icon-preview">
            <img id="preview-app-icon" src="<?php echo e(data.url); ?>" alt="Просмотреть как иконку приложения"/>
        </div>
    </script>
    <script src='/js/larchik/underscore.min.js'></script>
    <script src='/js/larchik/shortcode.js'></script>
    <script src='/js/larchik/backbone.min.js'></script>
    <script src='/js/larchik/wp-util.js'></script>
    <script src='/js/larchik/wp-backbone.js'></script>
    <script src='/js/larchik/media-models.js'></script>
    <script src='/js/larchik/plupload/wp-plupload.js'></script>
    <script src='/js/larchik/jquery/ui/core.min.js'></script>
    <script src='/js/larchik/jquery/ui/widget.min.js'></script>
    <script src='/js/larchik/jquery/ui/mouse.min.js'></script>
    <script src='/js/larchik/jquery/ui/sortable.min.js'></script>
    <script src='/js/larchik/mediaelement/mediaelement-and-player.js'></script>
    <script src='/js/larchik/mediaelement/wp-mediaelement.js'></script>
    <script src='/js/larchik/media-views.js'></script>
    <script src='/js/larchik/media-editor.js'></script>
    <script src='/js/larchik/media-audiovideo.js'></script>
    
    <script src='/js/larchik/editor.js'></script>
    <script src='/js/larchik/wp-embed.js'></script>
    <script src='/js/larchik/media-buttons.js'></script>
    <script src='/js/larchik/quicktags.min.js'></script>
    <script src='/js/larchik/wp-a11y.min.js'></script>
    <script src='/js/larchik/wplink.min.js'></script>
    <script src='/js/larchik/jquery/ui/position.min.js'></script>
    <script src='/js/larchik/jquery/ui/menu.min.js'></script>
    <script src='/js/larchik/jquery/ui/autocomplete.min.js'></script>
    <script src='/js/larchik/thickbox/thickbox.js'></script>
    <script src='/js/larchik/media-upload.min.js'></script>
    <script src='/js/larchik/tinymce/tinymce.min.js'></script>
    <script src='/js/larchik/tinymce/plugins/compat3x/plugin.min.js'></script>
    <script>
        tinymce.addI18n( 'ru', <?php echo $tinymce; ?>);
        tinymce.ScriptLoader.markDone( '/js/larchik/tinymce/langs/ru.js' );
    </script>
    <script src='/js/larchik/tinymce/langs/wp-langs-en.js'></script>
    <script>
        var ajaxurl = "/admin/ajax";
        ( function() {
            var init, id, $wrap;

            if(typeof tinyMCEPreInit !== 'undefined'){
                if ( typeof tinymce !== 'undefined' ) {
                    if ( tinymce.Env.ie && tinymce.Env.ie < 11 ) {
                        tinymce.$( '.wp-editor-wrap ' ).removeClass( 'tmce-active' ).addClass( 'html-active' );
                        return;
                    }

                    for ( id in tinyMCEPreInit.mceInit ) {
                        init = tinyMCEPreInit.mceInit[id];
                        $wrap = tinymce.$( '#wp-' + id + '-wrap' );

                        if ( ( $wrap.hasClass( 'tmce-active' ) || ! tinyMCEPreInit.qtInit.hasOwnProperty( id ) ) && ! init.wp_skip_init ) {
                            tinymce.init( init );

                            if ( ! window.wpActiveEditor ) {
                                window.wpActiveEditor = id;
                            }
                        }
                    }
                }

                if ( typeof quicktags !== 'undefined' ) {
                    for ( id in tinyMCEPreInit.qtInit ) {
                        quicktags( tinyMCEPreInit.qtInit[id] );

                        if ( ! window.wpActiveEditor ) {
                            window.wpActiveEditor = id;
                        }
                    }
                }
            }
        }());
    </script>
<?php $__env->stopSection(); ?>
<?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/media/assets.blade.php ENDPATH**/ ?>