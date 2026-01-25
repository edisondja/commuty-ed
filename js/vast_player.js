/**
 * Reproductor de Video con soporte VAST
 * Utiliza Google IMA SDK para anuncios pre-roll, mid-roll y post-roll
 */

class VastPlayer {
    constructor(videoElementId, options = {}) {
        this.videoElement = document.getElementById(videoElementId);
        this.options = {
            skipDelay: options.skipDelay || 5,
            vastPreroll: options.vastPreroll || null,
            vastMidroll: options.vastMidroll || null,
            vastPostroll: options.vastPostroll || null,
            midrollTime: options.midrollTime || 30,
            onAdStart: options.onAdStart || null,
            onAdEnd: options.onAdEnd || null,
            onAdError: options.onAdError || null,
            ...options
        };
        
        this.adsManager = null;
        this.adsLoader = null;
        this.adDisplayContainer = null;
        this.isAdPlaying = false;
        this.midrollPlayed = false;
        this.imaLoaded = false;
        
        this.init();
    }
    
    /**
     * Inicializar el reproductor
     */
    init() {
        // Crear contenedor para anuncios
        this.createAdContainer();
        
        // Cargar Google IMA SDK si no está cargado
        if (typeof google === 'undefined' || typeof google.ima === 'undefined') {
            this.loadImaSDK().then(() => {
                this.setupIMA();
            }).catch(err => {
                console.warn('No se pudo cargar IMA SDK, reproduciendo sin anuncios:', err);
            });
        } else {
            this.setupIMA();
        }
        
        // Eventos del video principal
        this.setupVideoEvents();
    }
    
    /**
     * Cargar Google IMA SDK dinámicamente
     */
    loadImaSDK() {
        return new Promise((resolve, reject) => {
            if (document.querySelector('script[src*="imasdk"]')) {
                // Ya existe el script
                const checkIma = setInterval(() => {
                    if (typeof google !== 'undefined' && typeof google.ima !== 'undefined') {
                        clearInterval(checkIma);
                        this.imaLoaded = true;
                        resolve();
                    }
                }, 100);
                setTimeout(() => {
                    clearInterval(checkIma);
                    reject('Timeout esperando IMA SDK');
                }, 5000);
                return;
            }
            
            const script = document.createElement('script');
            script.src = 'https://imasdk.googleapis.com/js/sdkloader/ima3.js';
            script.async = true;
            script.onload = () => {
                this.imaLoaded = true;
                resolve();
            };
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }
    
    /**
     * Crear contenedor para anuncios
     */
    createAdContainer() {
        const parent = this.videoElement.parentElement;
        parent.style.position = 'relative';
        
        // Contenedor de anuncios
        this.adContainer = document.createElement('div');
        this.adContainer.id = 'vast-ad-container-' + this.videoElement.id;
        this.adContainer.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            display: none;
            background: #000;
        `;
        parent.appendChild(this.adContainer);
        
        // Botón de saltar
        this.skipButton = document.createElement('button');
        this.skipButton.className = 'vast-skip-button';
        this.skipButton.style.cssText = `
            position: absolute;
            bottom: 60px;
            right: 20px;
            background: rgba(0,0,0,0.7);
            color: white;
            border: 1px solid white;
            padding: 10px 20px;
            cursor: pointer;
            z-index: 101;
            display: none;
            font-size: 14px;
            border-radius: 4px;
        `;
        this.skipButton.onclick = () => this.skipAd();
        parent.appendChild(this.skipButton);
        
        // Indicador de anuncio
        this.adIndicator = document.createElement('div');
        this.adIndicator.className = 'vast-ad-indicator';
        this.adIndicator.style.cssText = `
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255, 193, 7, 0.9);
            color: #000;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 3px;
            z-index: 101;
            display: none;
        `;
        this.adIndicator.textContent = 'Publicidad';
        parent.appendChild(this.adIndicator);
    }
    
    /**
     * Configurar Google IMA
     */
    setupIMA() {
        if (!this.imaLoaded) return;
        
        try {
            this.adDisplayContainer = new google.ima.AdDisplayContainer(
                this.adContainer,
                this.videoElement
            );
            
            this.adsLoader = new google.ima.AdsLoader(this.adDisplayContainer);
            
            this.adsLoader.addEventListener(
                google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED,
                (e) => this.onAdsManagerLoaded(e),
                false
            );
            
            this.adsLoader.addEventListener(
                google.ima.AdErrorEvent.Type.AD_ERROR,
                (e) => this.onAdError(e),
                false
            );
            
            // Solicitar pre-roll al iniciar
            if (this.options.vastPreroll) {
                this.videoElement.addEventListener('play', () => {
                    if (!this.isAdPlaying && !this.prerollPlayed) {
                        this.requestAds(this.options.vastPreroll);
                        this.prerollPlayed = true;
                    }
                }, { once: true });
            }
        } catch (e) {
            console.error('Error configurando IMA:', e);
        }
    }
    
    /**
     * Configurar eventos del video
     */
    setupVideoEvents() {
        // Mid-roll
        if (this.options.vastMidroll) {
            this.videoElement.addEventListener('timeupdate', () => {
                if (!this.midrollPlayed && 
                    this.videoElement.currentTime >= this.options.midrollTime &&
                    !this.isAdPlaying) {
                    this.midrollPlayed = true;
                    this.requestAds(this.options.vastMidroll);
                }
            });
        }
        
        // Post-roll
        if (this.options.vastPostroll) {
            this.videoElement.addEventListener('ended', () => {
                if (!this.postrollPlayed) {
                    this.postrollPlayed = true;
                    this.requestAds(this.options.vastPostroll);
                }
            });
        }
    }
    
    /**
     * Solicitar anuncios
     */
    requestAds(vastUrl) {
        if (!this.adsLoader || !vastUrl) return;
        
        this.videoElement.pause();
        
        const adsRequest = new google.ima.AdsRequest();
        adsRequest.adTagUrl = vastUrl;
        adsRequest.linearAdSlotWidth = this.videoElement.offsetWidth;
        adsRequest.linearAdSlotHeight = this.videoElement.offsetHeight;
        
        this.adsLoader.requestAds(adsRequest);
    }
    
    /**
     * Ads Manager cargado
     */
    onAdsManagerLoaded(adsManagerLoadedEvent) {
        const adsRenderingSettings = new google.ima.AdsRenderingSettings();
        adsRenderingSettings.restoreCustomPlaybackStateOnAdBreakComplete = true;
        
        this.adsManager = adsManagerLoadedEvent.getAdsManager(
            this.videoElement,
            adsRenderingSettings
        );
        
        // Eventos del ad manager
        this.adsManager.addEventListener(
            google.ima.AdEvent.Type.STARTED,
            () => this.onAdStarted()
        );
        
        this.adsManager.addEventListener(
            google.ima.AdEvent.Type.COMPLETE,
            () => this.onAdComplete()
        );
        
        this.adsManager.addEventListener(
            google.ima.AdEvent.Type.SKIPPED,
            () => this.onAdComplete()
        );
        
        this.adsManager.addEventListener(
            google.ima.AdErrorEvent.Type.AD_ERROR,
            (e) => this.onAdError(e)
        );
        
        try {
            this.adDisplayContainer.initialize();
            this.adsManager.init(
                this.videoElement.offsetWidth,
                this.videoElement.offsetHeight,
                google.ima.ViewMode.NORMAL
            );
            this.adsManager.start();
        } catch (e) {
            console.error('Error iniciando ads:', e);
            this.videoElement.play();
        }
    }
    
    /**
     * Anuncio iniciado
     */
    onAdStarted() {
        this.isAdPlaying = true;
        this.adContainer.style.display = 'block';
        this.adIndicator.style.display = 'block';
        this.videoElement.style.display = 'none';
        
        // Temporizador para botón skip
        let skipCountdown = this.options.skipDelay;
        this.skipButton.textContent = `Saltar en ${skipCountdown}s`;
        this.skipButton.style.display = 'block';
        this.skipButton.disabled = true;
        
        const skipInterval = setInterval(() => {
            skipCountdown--;
            if (skipCountdown <= 0) {
                clearInterval(skipInterval);
                this.skipButton.textContent = 'Saltar anuncio ▶';
                this.skipButton.disabled = false;
            } else {
                this.skipButton.textContent = `Saltar en ${skipCountdown}s`;
            }
        }, 1000);
        
        if (this.options.onAdStart) {
            this.options.onAdStart();
        }
    }
    
    /**
     * Anuncio completado
     */
    onAdComplete() {
        this.isAdPlaying = false;
        this.adContainer.style.display = 'none';
        this.adIndicator.style.display = 'none';
        this.skipButton.style.display = 'none';
        this.videoElement.style.display = 'block';
        
        // Continuar video principal
        this.videoElement.play();
        
        if (this.options.onAdEnd) {
            this.options.onAdEnd();
        }
    }
    
    /**
     * Error en anuncio
     */
    onAdError(adErrorEvent) {
        console.warn('Error VAST:', adErrorEvent.getError());
        
        if (this.adsManager) {
            this.adsManager.destroy();
        }
        
        this.isAdPlaying = false;
        this.adContainer.style.display = 'none';
        this.adIndicator.style.display = 'none';
        this.skipButton.style.display = 'none';
        this.videoElement.style.display = 'block';
        
        // Continuar video aunque falle el anuncio
        this.videoElement.play();
        
        if (this.options.onAdError) {
            this.options.onAdError(adErrorEvent.getError());
        }
    }
    
    /**
     * Saltar anuncio
     */
    skipAd() {
        if (this.adsManager) {
            this.adsManager.skip();
        }
    }
    
    /**
     * Destruir reproductor
     */
    destroy() {
        if (this.adsManager) {
            this.adsManager.destroy();
        }
        if (this.adsLoader) {
            this.adsLoader.destroy();
        }
        if (this.adContainer) {
            this.adContainer.remove();
        }
        if (this.skipButton) {
            this.skipButton.remove();
        }
        if (this.adIndicator) {
            this.adIndicator.remove();
        }
    }
}

/**
 * Función para inicializar reproductor VAST desde configuración del servidor
 */
async function initVastPlayerFromConfig(videoElementId) {
    try {
        const formData = new FormData();
        formData.append('action', 'obtener_reproductor_default');
        
        const response = await axios.post(
            '/controllers/actions_board.php',
            formData
        );
        
        if (response.data.success && response.data.reproductor) {
            const rep = response.data.reproductor;
            return new VastPlayer(videoElementId, {
                vastPreroll: rep.vast_url || null,
                vastMidroll: rep.vast_url_mid || null,
                vastPostroll: rep.vast_url_post || null,
                skipDelay: parseInt(rep.skip_delay) || 5,
                midrollTime: parseInt(rep.mid_roll_time) || 30
            });
        }
    } catch (e) {
        console.warn('No se pudo cargar configuración VAST:', e);
    }
    
    return null;
}

// Exportar para uso global
if (typeof window !== 'undefined') {
    window.VastPlayer = VastPlayer;
    window.initVastPlayerFromConfig = initVastPlayerFromConfig;
}
