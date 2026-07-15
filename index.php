<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP SEO Library - Interactive Guide & 3D Playground</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Three.js and GSAP CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(17, 24, 39, 0.7);
            --border-color: rgba(255, 255, 255, 0.08);
            --accent-glow: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #ec4899 100%);
            --accent-primary: #3b82f6;
            --accent-secondary: #8b5cf6;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --text-success: #10b981;
            --text-error: #ef4444;
        }

        /* Reset and Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0d1321;
        }
        ::-webkit-scrollbar-thumb {
            background: #1f2937;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #374151;
        }

        /* 3D Canvas Container */
        #webgl-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
            pointer-events: none;
        }

        /* Layout Structure */
        .app-container {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Hero Header */
        header {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            padding-top: 4rem;
        }

        .badge {
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #60a5fa;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
            display: inline-block;
            backdrop-filter: blur(10px);
        }

        h1 {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, #ffffff, #9ca3af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.02em;
        }

        .tagline {
            font-size: 1.4rem;
            color: var(--text-muted);
            max-width: 600px;
            margin-bottom: 2.5rem;
            font-weight: 300;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: var(--accent-glow);
            color: #fff;
            border: none;
            box-shadow: 0 4px 20px rgba(139, 92, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(139, 92, 246, 0.5);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            border: 1px solid var(--border-color);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* Scroll indicator */
        .scroll-down {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0) translateX(-50%); }
            40% { transform: translateY(-10px) translateX(-50%); }
            60% { transform: translateY(-5px) translateX(-50%); }
        }

        /* Section layout */
        section {
            padding: 8rem 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .section-header {
            margin-bottom: 4rem;
            max-width: 700px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #fff, #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section-desc {
            color: var(--text-muted);
            font-size: 1.1rem;
            font-weight: 300;
        }

        /* Grid features */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 2.5rem;
            border-radius: 16px;
            backdrop-filter: blur(12px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--accent-glow);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(139, 92, 246, 0.4);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            display: inline-block;
        }

        .feature-card h3 {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 300;
        }

        /* Playground Layout */
        .playground-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            background: rgba(10, 15, 30, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2.5rem;
            backdrop-filter: blur(16px);
        }

        @media (max-width: 900px) {
            .playground-container {
                grid-template-columns: 1fr;
            }
        }

        .playground-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        input, select, textarea {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent-primary);
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.2);
        }

        /* Outputs Panel */
        .playground-outputs {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .output-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .output-tabs {
            display: flex;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.2rem;
            border-radius: 6px;
        }

        .tab-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.3s;
        }

        .tab-btn.active {
            background: var(--accent-secondary);
            color: #fff;
        }

        .code-block {
            background: #050811;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 1.5rem;
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
            overflow-x: auto;
            flex-grow: 1;
            min-height: 250px;
            white-space: pre-wrap;
            color: #a5b4fc;
        }

        .validation-status {
            padding: 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-valid {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--text-success);
        }

        .status-invalid {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--text-error);
        }

        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .tag {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .tag.active {
            background: var(--accent-primary);
            border-color: var(--accent-primary);
        }

        /* Links list for Sitemap builder */
        .sitemap-links-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            padding: 1rem;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.2);
        }

        .sitemap-link-row {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .sitemap-link-row input, .sitemap-link-row select {
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        .remove-row-btn {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--text-error);
            border-radius: 6px;
            padding: 0.5rem 0.8rem;
            cursor: pointer;
            font-weight: bold;
        }

        .remove-row-btn:hover {
            background: rgba(239, 68, 68, 0.3);
        }

        /* Footer */
        footer {
            padding: 4rem 0;
            border-top: 1px solid var(--border-color);
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>

    <!-- 3D Background Canvas -->
    <div id="webgl-container"></div>

    <div class="app-container">
        
        <!-- HERO SECTION -->
        <header id="hero">
            <span class="badge">melbahja/seo php library</span>
            <h1>Supercharge Your PHP On-Page SEO</h1>
            <p class="tagline">An elegant, zero-dependency PHP 8.1+ library to generate structured data, meta tags, sitemaps, and indexing requests with built-in schema validation.</p>
            <div class="cta-buttons">
                <a href="#features" class="btn btn-primary">Explore Features</a>
                <a href="#playground" class="btn btn-secondary">Try Sandbox</a>
            </div>
            <div class="scroll-down">
                <span>Scroll down to see the magic</span>
                <span>↓</span>
            </div>
        </header>

        <!-- FEATURES GUIDE -->
        <section id="features">
            <div class="section-header">
                <span class="badge">documentation & capabilities</span>
                <h2 class="section-title">What the Library Can Do</h2>
                <p class="section-desc">Zero external dependencies. Designed for high performance and strict PSR-4 standards.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <span class="feature-icon">👷</span>
                    <h3>Structured Data (JSON-LD)</h3>
                    <p>Easily generate rich Google Search schema.org graphs. Includes object graphs, complex nested elements, and automated JSON serialization.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">🛀</span>
                    <h3>Meta Tags Engine</h3>
                    <p>Build page metadata including Canonical links, Hreflang alternates, OpenGraph tags, X (Twitter) cards, custom robots directives, and search engine verification tokens.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">🗺</span>
                    <h3>XML Sitemaps Generator</h3>
                    <p>Generate highly custom sitemaps with multi-mode outputs (disk writing, streams, inline string/memory). Full support for Images, Videos, News, and localized/multilingual URLs.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">📤</span>
                    <h3>Search Indexing APIs</h3>
                    <p>Submit newly published or updated URLs instantly to Google Indexing API and the global IndexNow protocols (supported by Bing, Yandex, etc.) for rapid indexation.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">✅</span>
                    <h3>Schema & Robots Validator</h3>
                    <p>Validate your Schema objects and robots.txt files locally. Ensure compliance with schema.org specifications and correct formatting before deployment.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">🧩</span>
                    <h3>Zero External Packages</h3>
                    <p>Runs natively without external library clutter. Extensively tested across standard XML, JSON, and curl modules. Quick and lightweight.</p>
                </div>
            </div>
        </section>

        <!-- PLAYGROUND SANDBOX -->
        <section id="playground">
            <div class="section-header">
                <span class="badge">interactive sandbox</span>
                <h2 class="section-title">Live Testing Playground</h2>
                <p class="section-desc">Interact with the real PHP SEO library running locally on your server. Generate code and validate outputs in real-time.</p>
            </div>

            <div style="margin-bottom: 2rem;">
                <span class="badge" style="margin-bottom: 0.5rem;">Select Playground Mode</span>
                <div class="tag-list" id="playground-modes">
                    <div class="tag active" data-mode="schema">👷 Schema.org JSON-LD</div>
                    <div class="tag" data-mode="metatags">🛀 Meta Tags & OpenGraph</div>
                    <div class="tag" data-mode="sitemaps">🗺 XML Sitemaps Builder</div>
                    <div class="tag" data-mode="validator">✅ Rich Validator</div>
                </div>
            </div>

            <!-- SCHEMA PLAYGROUND -->
            <div class="playground-container" id="sandbox-schema">
                <div class="playground-form">
                    <h3 style="font-weight: 600;">Schema.org Generator</h3>
                    
                    <div class="form-group">
                        <label for="schema-type">Schema Type</label>
                        <select id="schema-type">
                            <option value="Organization">Organization</option>
                            <option value="Product">Product</option>
                            <option value="WebPage">WebPage</option>
                            <option value="LocalBusiness">LocalBusiness</option>
                        </select>
                    </div>

                    <div id="schema-fields">
                        <!-- Dynamic fields injected based on selection -->
                    </div>

                    <button class="btn btn-primary" onclick="generateSchema()">Generate JSON-LD Schema</button>
                </div>

                <div class="playground-outputs">
                    <div class="output-header">
                        <h4 style="font-weight: 600;">Result & Output</h4>
                        <div class="output-tabs">
                            <button class="tab-btn active" onclick="switchOutputTab('schema', 'json')">JSON-LD</button>
                            <button class="tab-btn" onclick="switchOutputTab('schema', 'php')">PHP Code</button>
                        </div>
                    </div>

                    <div id="schema-output-json" class="code-block">&lt;script type="application/ld+json"&gt;&lt;/script&gt;</div>
                    <div id="schema-output-php" class="code-block" style="display: none;">PHP code will appear here.</div>

                    <div id="schema-validation-box" class="validation-status status-valid" style="display: none;">
                        <span>✓ Schema is valid!</span>
                    </div>
                </div>
            </div>

            <!-- METATAGS PLAYGROUND -->
            <div class="playground-container" id="sandbox-metatags" style="display: none;">
                <div class="playground-form">
                    <h3 style="font-weight: 600;">Meta Tags Generator</h3>
                    
                    <div class="form-group">
                        <label for="meta-title">Page Title</label>
                        <input type="text" id="meta-title" value="My Website Home">
                    </div>

                    <div class="form-group">
                        <label for="meta-desc">Description</label>
                        <input type="text" id="meta-desc" value="The best search engine optimized site.">
                    </div>

                    <div class="form-group">
                        <label for="meta-canonical">Canonical URL</label>
                        <input type="text" id="meta-canonical" value="https://mysite.com">
                    </div>

                    <div class="form-group">
                        <label for="meta-image">OpenGraph Image URL</label>
                        <input type="text" id="meta-image" value="https://mysite.com/banner.jpg">
                    </div>

                    <div class="form-group">
                        <label for="meta-twitter">Twitter Creator Handle</label>
                        <input type="text" id="meta-twitter" value="@my_handle">
                    </div>

                    <div class="form-group">
                        <label for="meta-robots">Robots Directives</label>
                        <div style="display: flex; gap: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; text-transform: none; font-weight: normal;">
                                <input type="checkbox" id="meta-robot-index" checked> Index
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; text-transform: none; font-weight: normal;">
                                <input type="checkbox" id="meta-robot-follow" checked> Follow
                            </label>
                        </div>
                    </div>

                    <button class="btn btn-primary" onclick="generateMetaTags()">Compile Meta Tags</button>
                </div>

                <div class="playground-outputs">
                    <div class="output-header">
                        <h4 style="font-weight: 600;">Result & Output</h4>
                        <div class="output-tabs">
                            <button class="tab-btn active" onclick="switchOutputTab('meta', 'html')">HTML Output</button>
                            <button class="tab-btn" onclick="switchOutputTab('meta', 'php')">PHP Code</button>
                        </div>
                    </div>

                    <div id="meta-output-html" class="code-block">HTML outputs will load here.</div>
                    <div id="meta-output-php" class="code-block" style="display: none;">PHP compilation will load here.</div>
                </div>
            </div>

            <!-- SITEMAPS PLAYGROUND -->
            <div class="playground-container" id="sandbox-sitemaps" style="display: none;">
                <div class="playground-form">
                    <h3 style="font-weight: 600;">XML Sitemaps Builder</h3>
                    
                    <div class="form-group">
                        <label for="sitemap-base">Base URL</label>
                        <input type="text" id="sitemap-base" value="https://example.com">
                    </div>

                    <div class="form-group">
                        <label style="display: flex; justify-content: space-between; align-items: center;">
                            <span>Sitemap Links</span>
                            <button class="btn btn-secondary" style="padding: 0.2rem 0.6rem; font-size: 0.8rem;" onclick="addSitemapRow()">+ Add Link</button>
                        </label>
                        <div class="sitemap-links-list" id="sitemap-rows">
                            <!-- Rows loaded here -->
                        </div>
                    </div>

                    <button class="btn btn-primary" onclick="generateSitemap()">Compile Sitemaps</button>
                </div>

                <div class="playground-outputs">
                    <div class="output-header">
                        <h4 style="font-weight: 600;">Result & Output</h4>
                        <div class="output-tabs">
                            <button class="tab-btn active" onclick="switchOutputTab('sitemap', 'blog')">blog.xml</button>
                            <button class="tab-btn" onclick="switchOutputTab('sitemap', 'index')">sitemap.xml (Index)</button>
                            <button class="tab-btn" onclick="switchOutputTab('sitemap', 'php')">PHP Code</button>
                        </div>
                    </div>

                    <div id="sitemap-output-blog" class="code-block">XML elements will appear here.</div>
                    <div id="sitemap-output-index" class="code-block" style="display: none;">Index sitemap xml will appear here.</div>
                    <div id="sitemap-output-php" class="code-block" style="display: none;">PHP sitemap syntax.</div>
                </div>
            </div>

            <!-- VALIDATOR PLAYGROUND -->
            <div class="playground-container" id="sandbox-validator" style="display: none;">
                <div class="playground-form">
                    <h3 style="font-weight: 600;">Rich Validator Sandbox</h3>
                    
                    <div class="form-group">
                        <label for="validator-type">Validation Target</label>
                        <select id="validator-type" onchange="updateValidatorPlaceholder()">
                            <option value="schema">Schema.org JSON-LD</option>
                            <option value="robots">Robots.txt Rules</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="validator-content">Paste Raw Content</label>
                        <textarea id="validator-content" rows="10" placeholder='Pate your JSON-LD here, e.g.:&#10;{&#10;  "@type": "Product",&#10;  "name": "Super Widget",&#10;  "sku": "SW-100"&#10;}'></textarea>
                    </div>

                    <button class="btn btn-primary" onclick="runValidation()">Run Validator</button>
                </div>

                <div class="playground-outputs">
                    <h4 style="font-weight: 600;">Validation Results</h4>
                    
                    <div id="validator-result-box" class="validation-status status-valid" style="display: none; min-height: 100px; flex-direction: column; align-items: flex-start; justify-content: center; gap: 0.5rem;">
                        <!-- Validation feedback text loaded here -->
                    </div>
                </div>
            </div>

        </section>

        <!-- FOOTER -->
        <footer>
            <p>&copy; 2026 PHP SEO Library. Built locally with Three.js & Google Antigravity.</p>
        </footer>
    </div>

    <!-- Three.js + Scroll animation Script -->
    <script>
        // --- THREE.JS SCENE SETUP ---
        const container = document.getElementById('webgl-container');
        const scene = new THREE.Scene();
        
        // Dark background matching layout
        scene.background = new THREE.Color(0x070a13);
        scene.fog = new THREE.FogExp2(0x070a13, 0.05);

        const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 100);
        camera.position.z = 20;

        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(window.innerWidth, window.innerHeight);
        container.appendChild(renderer.domElement);

        // Create SEO Node Network Representation
        const nodeGroup = new THREE.Group();
        scene.add(nodeGroup);

        const particleCount = 120;
        const particleGeometry = new THREE.BufferGeometry();
        const positions = new Float32Array(particleCount * 3);
        const colors = new Float32Array(particleCount * 3);

        const nodePositions = [];
        const baseColor1 = new THREE.Color(0x3b82f6); // blue
        const baseColor2 = new THREE.Color(0x8b5cf6); // purple
        const baseColor3 = new THREE.Color(0xec4899); // pink

        for (let i = 0; i < particleCount; i++) {
            // Position inside an abstract cloud/sphere
            const theta = Math.random() * Math.PI * 2;
            const phi = Math.acos((Math.random() * 2) - 1);
            const distance = 8 + Math.random() * 6;

            const x = distance * Math.sin(phi) * Math.cos(theta);
            const y = distance * Math.sin(phi) * Math.sin(theta);
            const z = distance * Math.cos(phi);

            positions[i * 3] = x;
            positions[i * 3 + 1] = y;
            positions[i * 3 + 2] = z;
            nodePositions.push(new THREE.Vector3(x, y, z));

            // Random mixed color
            const mixColor = baseColor1.clone().lerp(baseColor2, Math.random());
            colors[i * 3] = mixColor.r;
            colors[i * 3 + 1] = mixColor.g;
            colors[i * 3 + 2] = mixColor.b;
        }

        particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        particleGeometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

        // Create glowing dots
        const loader = new THREE.TextureLoader();
        // Fallback transparent circle programmatically generated texture since we shouldn't use external links for textures
        const size = 16;
        const canvas = document.createElement('canvas');
        canvas.width = size;
        canvas.height = size;
        const ctx = canvas.getContext('2d');
        const grad = ctx.createRadialGradient(size/2, size/2, 0, size/2, size/2, size/2);
        grad.addColorStop(0, 'rgba(255,255,255,1)');
        grad.addColorStop(1, 'rgba(255,255,255,0)');
        ctx.fillStyle = grad;
        ctx.fillRect(0,0,size,size);
        const pointTexture = new THREE.CanvasTexture(canvas);

        const pointMaterial = new THREE.PointsMaterial({
            size: 0.5,
            vertexColors: true,
            map: pointTexture,
            transparent: true,
            blending: THREE.AdditiveBlending,
            depthWrite: false
        });

        const points = new THREE.Points(particleGeometry, pointMaterial);
        nodeGroup.add(points);

        // Create web lines between closer nodes
        const lineMaterial = new THREE.LineBasicMaterial({
            color: 0x4f46e5,
            transparent: true,
            opacity: 0.15,
            blending: THREE.AdditiveBlending
        });

        const linePositions = [];
        const maxDist = 4.5;
        for (let i = 0; i < particleCount; i++) {
            for (let j = i + 1; j < particleCount; j++) {
                const dist = nodePositions[i].distanceTo(nodePositions[j]);
                if (dist < maxDist) {
                    linePositions.push(nodePositions[i].x, nodePositions[i].y, nodePositions[i].z);
                    linePositions.push(nodePositions[j].x, nodePositions[j].y, nodePositions[j].z);
                }
            }
        }

        const lineGeometry = new THREE.BufferGeometry();
        lineGeometry.setAttribute('position', new THREE.Float32BufferAttribute(linePositions, 3));
        const lines = new THREE.LineSegments(lineGeometry, lineMaterial);
        nodeGroup.add(lines);

        // Add soft global lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight.position.set(5, 5, 5);
        scene.add(directionalLight);

        // --- RESIZE EVENT ---
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        // --- SCROLL ANIMATION LINKING (GSAP + SCROLL) ---
        let scrollY = 0;
        window.addEventListener('scroll', () => {
            scrollY = window.scrollY;
        });

        // Frame rendering loop
        const animate = () => {
            requestAnimationFrame(animate);

            // Auto-rotation
            nodeGroup.rotation.y += 0.001;
            nodeGroup.rotation.x += 0.0005;

            // Scroll influence
            const scrollRatio = scrollY / (document.documentElement.scrollHeight - window.innerHeight || 1);
            
            // Camera position shifting dynamically along Z and rotating slightly on scroll
            camera.position.z = 20 - (scrollRatio * 8);
            camera.position.y = (scrollRatio * 5);
            nodeGroup.rotation.y = scrollRatio * Math.PI * 1.5;
            
            // Adjust point sizes programmatically
            pointMaterial.size = 0.5 + (scrollRatio * 0.4);

            renderer.render(scene, camera);
        };
        animate();


        // --- INTERACTIVE PLAYGROUND ACTIONS ---

        // Config variables
        const activePlaygroundTabs = {
            schema: 'json',
            meta: 'html',
            sitemap: 'blog'
        };

        // Switch playground tab display
        function switchOutputTab(section, tab) {
            activePlaygroundTabs[section] = tab;
            
            if (section === 'schema') {
                document.getElementById('schema-output-json').style.display = tab === 'json' ? 'block' : 'none';
                document.getElementById('schema-output-php').style.display = tab === 'php' ? 'block' : 'none';
                document.querySelector('[onclick="switchOutputTab(\'schema\', \'json\')"]').classList.toggle('active', tab === 'json');
                document.querySelector('[onclick="switchOutputTab(\'schema\', \'php\')"]').classList.toggle('active', tab === 'php');
            } else if (section === 'meta') {
                document.getElementById('meta-output-html').style.display = tab === 'html' ? 'block' : 'none';
                document.getElementById('meta-output-php').style.display = tab === 'php' ? 'block' : 'none';
                document.querySelector('[onclick="switchOutputTab(\'meta\', \'html\')"]').classList.toggle('active', tab === 'html');
                document.querySelector('[onclick="switchOutputTab(\'meta\', \'php\')"]').classList.toggle('active', tab === 'php');
            } else if (section === 'sitemap') {
                document.getElementById('sitemap-output-blog').style.display = tab === 'blog' ? 'block' : 'none';
                document.getElementById('sitemap-output-index').style.display = tab === 'index' ? 'block' : 'none';
                document.getElementById('sitemap-output-php').style.display = tab === 'php' ? 'block' : 'none';
                document.querySelector('[onclick="switchOutputTab(\'sitemap\', \'blog\')"]').classList.toggle('active', tab === 'blog');
                document.querySelector('[onclick="switchOutputTab(\'sitemap\', \'index\')"]').classList.toggle('active', tab === 'index');
                document.querySelector('[onclick="switchOutputTab(\'sitemap\', \'php\')"]').classList.toggle('active', tab === 'php');
            }
        }

        // Toggle overall sandbox modes
        document.getElementById('playground-modes').addEventListener('click', (e) => {
            const tag = e.target.closest('.tag');
            if (!tag) return;
            
            document.querySelectorAll('#playground-modes .tag').forEach(t => t.classList.remove('active'));
            tag.classList.add('active');

            const mode = tag.dataset.mode;
            document.getElementById('sandbox-schema').style.display = mode === 'schema' ? 'grid' : 'none';
            document.getElementById('sandbox-metatags').style.display = mode === 'metatags' ? 'grid' : 'none';
            document.getElementById('sandbox-sitemaps').style.display = mode === 'sitemaps' ? 'grid' : 'none';
            document.getElementById('sandbox-validator').style.display = mode === 'validator' ? 'grid' : 'none';
        });

        // 1. Dynamic Schema Fields
        const schemaTypeSelector = document.getElementById('schema-type');
        const schemaFieldsContainer = document.getElementById('schema-fields');

        const fieldsTemplates = {
            Organization: `
                <div class="form-group">
                    <label for="org-name">Organization Name</label>
                    <input type="text" id="org-name" value="PHP SEO Org">
                </div>
                <div class="form-group">
                    <label for="org-url">URL</label>
                    <input type="text" id="org-url" value="https://seo-org.com">
                </div>
                <div class="form-group">
                    <label for="org-logo">Logo URL</label>
                    <input type="text" id="org-logo" value="https://seo-org.com/logo.png">
                </div>
            `,
            Product: `
                <div class="form-group">
                    <label for="prod-name">Product Name</label>
                    <input type="text" id="prod-name" value="Premium SEO Pack">
                </div>
                <div class="form-group">
                    <label for="prod-sku">SKU</label>
                    <input type="text" id="prod-sku" value="SEO-PKG-1">
                </div>
                <div class="form-group">
                    <label for="prod-price">Price</label>
                    <input type="text" id="prod-price" value="99.99">
                </div>
                <div class="form-group">
                    <label for="prod-desc">Description</label>
                    <input type="text" id="prod-desc" value="Unlocks fully optimized web capabilities instantly.">
                </div>
            `,
            WebPage: `
                <div class="form-group">
                    <label for="page-name">Page Name</label>
                    <input type="text" id="page-name" value="Home Page">
                </div>
                <div class="form-group">
                    <label for="page-url">Page URL</label>
                    <input type="text" id="page-url" value="https://example.com/home">
                </div>
            `,
            LocalBusiness: `
                <div class="form-group">
                    <label for="biz-name">Business Name</label>
                    <input type="text" id="biz-name" value="The Local Bistro">
                </div>
                <div class="form-group">
                    <label for="biz-url">Website URL</label>
                    <input type="text" id="biz-url" value="https://localbistro.com">
                </div>
                <div class="form-group">
                    <label for="biz-phone">Phone</label>
                    <input type="text" id="biz-phone" value="+1-555-0199">
                </div>
                <div class="form-group">
                    <label for="biz-price">Price Range</label>
                    <input type="text" id="biz-price" value="$$">
                </div>
            `
        };

        function updateSchemaFields() {
            const selected = schemaTypeSelector.value;
            schemaFieldsContainer.innerHTML = fieldsTemplates[selected] || '';
        }
        schemaTypeSelector.addEventListener('change', updateSchemaFields);
        updateSchemaFields();

        // 2. Fetch AJAX Schema
        async function generateSchema() {
            const type = schemaTypeSelector.value;
            const props = {};
            
            if (type === 'Organization') {
                props.name = document.getElementById('org-name').value;
                props.url = document.getElementById('org-url').value;
                props.logo = document.getElementById('org-logo').value;
            } else if (type === 'Product') {
                props.name = document.getElementById('prod-name').value;
                props.sku = document.getElementById('prod-sku').value;
                props.description = document.getElementById('prod-desc').value;
                props.offers = {
                    '@type': 'Offer',
                    'price': document.getElementById('prod-price').value,
                    'priceCurrency': 'USD',
                    'availability': 'https://schema.org/InStock'
                };
            } else if (type === 'WebPage') {
                props.name = document.getElementById('page-name').value;
                props.url = document.getElementById('page-url').value;
            } else if (type === 'LocalBusiness') {
                props.name = document.getElementById('biz-name').value;
                props.url = document.getElementById('biz-url').value;
                props.telephone = document.getElementById('biz-phone').value;
                props.priceRange = document.getElementById('biz-price').value;
                // Add required address structure to bypass address checks
                props.address = {
                    '@type': 'PostalAddress',
                    'streetAddress': '123 Main St',
                    'addressLocality': 'City',
                    'addressRegion': 'State',
                    'postalCode': '10001',
                    'addressCountry': 'US'
                };
            }

            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'schema', type, props })
                });
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('schema-output-json').textContent = data.output;
                    document.getElementById('schema-output-php').textContent = data.php;
                    
                    const validationBox = document.getElementById('schema-validation-box');
                    validationBox.style.display = 'flex';
                    if (data.errors === null) {
                        validationBox.className = 'validation-status status-valid';
                        validationBox.innerHTML = '<span>✓ Schema structure is valid according to library rules!</span>';
                    } else {
                        validationBox.className = 'validation-status status-invalid';
                        validationBox.innerHTML = '<span>✗ Validation errors:</span><br><ul style="padding-left: 1.5rem; font-size: 0.85rem;">' + data.errors.map(err => '<li>' + err + '</li>').join('') + '</ul>';
                    }
                } else {
                    alert('Error: ' + data.error);
                }
            } catch(e) {
                console.error(e);
            }
        }

        // 3. Fetch AJAX Meta Tags
        async function generateMetaTags() {
            const title = document.getElementById('meta-title').value;
            const description = document.getElementById('meta-desc').value;
            const canonical = document.getElementById('meta-canonical').value;
            const image = document.getElementById('meta-image').value;
            const twitter = document.getElementById('meta-twitter').value;
            
            const robots = [];
            if (document.getElementById('meta-robot-index').checked) robots.push('index');
            else robots.push('noindex');
            if (document.getElementById('meta-robot-follow').checked) robots.push('follow');
            else robots.push('nofollow');

            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'metatags', title, description, canonical, image, robots, twitter, og: 'website' })
                });
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('meta-output-html').textContent = data.output;
                    document.getElementById('meta-output-php').textContent = data.php;
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (e) {
                console.error(e);
            }
        }

        // 4. Sitemap rows builder
        const sitemapRowsContainer = document.getElementById('sitemap-rows');
        
        function addSitemapRow(loc = '/new-page', priority = '0.8', changeFreq = 'weekly', lastMod = '2026-07-15') {
            const row = document.createElement('div');
            row.className = 'sitemap-link-row';
            row.innerHTML = `
                <input type="text" class="row-loc" value="${loc}" placeholder="/path" style="flex-grow: 2;">
                <input type="text" class="row-priority" value="${priority}" placeholder="1.0" style="width: 60px;">
                <select class="row-freq">
                    <option value="always" ${changeFreq === 'always' ? 'selected' : ''}>always</option>
                    <option value="hourly" ${changeFreq === 'hourly' ? 'selected' : ''}>hourly</option>
                    <option value="daily" ${changeFreq === 'daily' ? 'selected' : ''}>daily</option>
                    <option value="weekly" ${changeFreq === 'weekly' ? 'selected' : ''}>weekly</option>
                    <option value="monthly" ${changeFreq === 'monthly' ? 'selected' : ''}>monthly</option>
                </select>
                <input type="text" class="row-lastmod" value="${lastMod}" placeholder="YYYY-MM-DD" style="width: 100px;">
                <button class="remove-row-btn" onclick="this.parentElement.remove()">×</button>
            `;
            sitemapRowsContainer.appendChild(row);
        }
        
        // Initial sitemap rows
        addSitemapRow('/', '1.0', 'daily', '2026-07-15');
        addSitemapRow('/about', '0.8', 'weekly', '2026-07-10');
        addSitemapRow('/blog/hello-world', '0.7', 'monthly', '2026-07-01');

        async function generateSitemap() {
            const baseUrl = document.getElementById('sitemap-base').value;
            const rows = sitemapRowsContainer.querySelectorAll('.sitemap-link-row');
            const links = [];
            
            rows.forEach(row => {
                links.push({
                    loc: row.querySelector('.row-loc').value,
                    priority: row.querySelector('.row-priority').value,
                    changeFreq: row.querySelector('.row-freq').value,
                    lastMod: row.querySelector('.row-lastmod').value
                });
            });

            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'sitemap', baseUrl, links })
                });
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('sitemap-output-blog').textContent = data.output['blog.xml'];
                    document.getElementById('sitemap-output-index').textContent = data.output['sitemap.xml'];
                    document.getElementById('sitemap-output-php').textContent = data.php;
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (e) {
                console.error(e);
            }
        }

        // 5. Validator update placeholder
        function updateValidatorPlaceholder() {
            const valType = document.getElementById('validator-type').value;
            const contentArea = document.getElementById('validator-content');
            if (valType === 'schema') {
                contentArea.placeholder = 'Paste your JSON-LD here, e.g.:\n{\n  "@type": "Product",\n  "name": "Super Widget",\n  "sku": "SW-100"\n}';
                contentArea.value = '{\n  "@context": "https://schema.org",\n  "@type": "LocalBusiness",\n  "name": "Casablanca Cafe",\n  "url": "https://example.com",\n  "telephone": "+212524111111",\n  "address": {\n    "@type": "PostalAddress",\n    "streetAddress": "123 Avenue Mohammed V",\n    "addressLocality": "Casablanca",\n    "addressRegion": "Casablanca-Settat",\n    "postalCode": "20000",\n    "addressCountry": "MA"\n  }\n}';
            } else {
                contentArea.placeholder = 'Paste robots.txt rules here, e.g.:\nUser-agent: *\nDisallow: /private';
                contentArea.value = 'User-agent: *\nDisallow: /admin\nAllow: /public\nCrawl-delay: 5\n\nSitemap: https://example.com/sitemap.xml';
            }
        }
        updateValidatorPlaceholder();

        async function runValidation() {
            const type = document.getElementById('validator-type').value;
            const content = document.getElementById('validator-content').value;
            
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'validate', type, content })
                });
                const data = await response.json();
                
                const resultBox = document.getElementById('validator-result-box');
                resultBox.style.display = 'flex';
                
                if (data.success) {
                    if (data.errors === null) {
                        resultBox.className = 'validation-status status-valid';
                        resultBox.innerHTML = '<span>✓ Content matches SEO rules! No validation errors found.</span>';
                    } else {
                        resultBox.className = 'validation-status status-invalid';
                        let html = '<span>✗ Validation errors:</span><ul style="padding-left: 1.5rem; font-size: 0.85rem; width: 100%;">' + data.errors.map(err => '<li>' + err + '</li>').join('') + '</ul>';
                        if (type === 'schema') {
                            html += '<button class="btn btn-secondary" style="margin-top: 1rem; width: 100%; border-color: rgba(139, 92, 246, 0.4); background: rgba(139, 92, 246, 0.15); color: white;" onclick="regenerateSchema()">✨ Auto-Fix & Regenerate Schema</button>';
                        }
                        resultBox.innerHTML = html;
                    }
                } else {
                    resultBox.className = 'validation-status status-invalid';
                    resultBox.innerHTML = '<span>✗ Error processing validation: ' + data.error + '</span>';
                }
            } catch (e) {
                console.error(e);
            }
        }

        async function regenerateSchema() {
            const content = document.getElementById('validator-content').value;
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'regenerate', content })
                });
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('validator-content').value = data.fixedContent;
                    
                    const resultBox = document.getElementById('validator-result-box');
                    if (data.errors === null) {
                        resultBox.className = 'validation-status status-valid';
                        resultBox.innerHTML = '<span>✓ Schema has been successfully fixed and regenerated! No validation errors left.</span>';
                    } else {
                        resultBox.className = 'validation-status status-invalid';
                        resultBox.innerHTML = '<span>✗ Schema was regenerated but some errors require manual fixing:</span><ul style="padding-left: 1.5rem; font-size: 0.85rem; width: 100%;">' + data.errors.map(err => '<li>' + err + '</li>').join('') + '</ul>' +
                        '<button class="btn btn-secondary" style="margin-top: 1rem; width: 100%; border-color: rgba(139, 92, 246, 0.4); background: rgba(139, 92, 246, 0.15); color: white;" onclick="regenerateSchema()">✨ Re-attempt Auto-Fix</button>';
                    }
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (e) {
                console.error(e);
            }
        }

        // Trigger default generations on load
        window.addEventListener('DOMContentLoaded', () => {
            generateSchema();
            generateMetaTags();
            generateSitemap();
        });
    </script>
</body>
</html>
