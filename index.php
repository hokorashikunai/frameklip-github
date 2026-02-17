<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FrameKlip - Jasa Editing Video Profesional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .navy {
            background-color: #0f172a; /* Lebih gelap dari #1e3a8a */
        }
        
        .orange {
            background-color: #f97316;
        }
        
        .text-navy {
            color: #1e3a8a;
        }
        
        .text-orange {
            color: #f97316;
        }
        
        .btn-orange {
            background-color: #f97316;
            color: white;
            transition: all 0.3s;
        }
        
        .btn-orange:hover {
            background-color: #ea580c;
            transform: translateY(-2px);
        }
        
        .btn-navy {
            background-color: #1e3a8a;
            color: white;
            transition: all 0.3s;
        }
        
        .btn-navy:hover {
            background-color: #1e40af;
            transform: translateY(-2px);
        }
        
        .hero-bg {
            background: linear-gradient(rgba(15, 23, 42, 0.92), rgba(15, 23, 42, 0.92)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%230f172a" width="1200" height="600"/><g fill="%23f97316" opacity="0.1"><circle cx="200" cy="150" r="100"/><circle cx="800" cy="400" r="150"/><circle cx="1000" cy="200" r="80"/></g><text x="600" y="300" font-family="Arial" font-size="120" fill="%23ffffff" text-anchor="middle" opacity="0.1">EDIT</text></svg>');
            background-size: cover;
            background-position: center;
        }
        
        .card-service {
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .card-service:hover {
            transform: translateY(-10px);
            border-color: #f97316;
            box-shadow: 0 20px 40px rgba(249, 115, 22, 0.2);
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6);
        }
        
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideIn 0.3s;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .loading {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #f97316;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="navy fixed w-full z-50 shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex items-center space-x-3">
                        <img src="logo.png" alt="FrameKlip Logo" class="h-14 w-14 object-cover rounded-full" style="background: transparent; padding: 2px;">
                        <h1 class="text-3xl font-bold text-white">Frame<span class="text-orange">Klip</span></h1>
                    </div>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="text-white font-semibold transition-all duration-300" onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='white'">Home</a>
                    <a href="#layanan" class="text-white font-semibold transition-all duration-300" onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='white'">Services</a>
                    <a href="#about" class="text-white font-semibold transition-all duration-300" onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='white'">About</a>
                    <a href="#contact" class="text-white font-semibold transition-all duration-300" onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='white'">Contact</a>
                </div>
                <button id="mobileMenuBtn" class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <div id="mobileMenu" class="hidden md:hidden mt-4 space-y-2">
                <a href="#home" class="block text-white hover:text-orange transition py-2">Home</a>
                <a href="#layanan" class="block text-white hover:text-orange transition py-2">Services</a>
                <a href="#about" class="block text-white hover:text-orange transition py-2">About</a>
                <a href="#contact" class="block text-white hover:text-orange transition py-2">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Banner -->
    <section id="home" class="hero-bg pt-32 pb-20 px-6">
        <div class="container mx-auto">
            <!-- Banner Image -->
            <div class="max-w-6xl mx-auto mb-8">
                <img src="banner.jpg" alt="FrameKlip Banner" class="w-full rounded-2xl shadow-2xl border-4 border-orange/30 hover:scale-105 transition-transform duration-300">
            </div>
            
            <!-- CTA Buttons -->
            <div class="text-center">
                <a href="#layanan" class="btn-orange px-8 py-4 rounded-full text-lg font-semibold inline-block shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                    🎬 Lihat Layanan & Harga
                </a>
            </div>
        </div>
    </section>

    <!-- Layanan Section -->
    <section id="layanan" class="py-20 px-6 bg-white">
        <div class="container mx-auto">
            <h2 class="text-4xl font-bold text-center text-navy mb-4">Layanan Kami</h2>
            <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
                Pilih paket yang sesuai dengan kebutuhan Anda
            </p>
            
            <!-- Regular Package -->
            <div class="mb-16">
                <h3 class="text-3xl font-bold text-navy mb-4 text-center">
                    <span class="orange text-white px-6 py-2 rounded-full">Paket Regular</span>
                </h3>
                <p class="text-center text-gray-600 mb-10 text-lg font-semibold">⏱️ Pengerjaan: 3-4 Hari Kerja</p>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="card-service bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                        <div class="orange text-white w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-navy mb-2 text-center">Edit Reels / Video Pendek</h4>
                        <div class="text-center mb-3">
                            <span class="text-3xl font-bold text-orange">Rp 15K</span>
                        </div>
                        <p class="text-gray-600 mb-6 text-center text-sm">Perfect untuk Instagram Reels, TikTok, dan YouTube Shorts</p>
                        <button onclick="showGuideModal('Edit Reels / Video Pendek', 'Regular')" class="btn-orange w-full py-3 rounded-lg font-semibold hover:scale-105 transition-transform">
                            Pesan Sekarang
                        </button>
                    </div>

                    <div class="card-service bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                        <div class="orange text-white w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-navy mb-2 text-center">Cinematic</h4>
                        <div class="text-center mb-3">
                            <span class="text-3xl font-bold text-orange">Rp 20K</span>
                        </div>
                        <p class="text-gray-600 mb-6 text-center text-sm">Video dengan kualitas sinematik untuk proyek premium</p>
                        <button onclick="showGuideModal('Cinematic', 'Regular')" class="btn-orange w-full py-3 rounded-lg font-semibold hover:scale-105 transition-transform">
                            Pesan Sekarang
                        </button>
                    </div>

                    <div class="card-service bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                        <div class="orange text-white w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-navy mb-2 text-center">Dokumenter</h4>
                        <div class="text-center mb-3">
                            <span class="text-3xl font-bold text-orange">Rp 50K</span>
                        </div>
                        <p class="text-gray-600 mb-6 text-center text-sm">Edit profesional untuk video dokumentasi dan cerita</p>
                        <button onclick="showGuideModal('Dokumenter', 'Regular')" class="btn-orange w-full py-3 rounded-lg font-semibold hover:scale-105 transition-transform">
                            Pesan Sekarang
                        </button>
                    </div>

                    <div class="card-service bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                        <div class="orange text-white w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-navy mb-2 text-center">Preset</h4>
                        <div class="text-center mb-3">
                            <span class="text-3xl font-bold text-orange">Rp 20K</span>
                        </div>
                        <p class="text-gray-600 mb-6 text-center text-sm">Preset LUT dan filter siap pakai untuk editing cepat</p>
                        <button onclick="showGuideModal('Preset', 'Regular')" class="btn-orange w-full py-3 rounded-lg font-semibold hover:scale-105 transition-transform">
                            Pesan Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-3xl font-bold text-navy mb-4 text-center">
                    <span class="navy text-white px-6 py-2 rounded-full">⚡ Paket Fast Track</span>
                </h3>
                <p class="text-center text-gray-600 mb-10 text-lg font-semibold">⚡ Pengerjaan: 1-2 Hari Kerja</p>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="card-service bg-white rounded-xl shadow-lg p-6 border-2 border-orange hover:shadow-2xl transition-shadow relative">
                        <div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse">
                            ⚡ FAST
                        </div>
                        <div class="navy text-white w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-navy mb-2 text-center">Edit Reels / Video Pendek</h4>
                        <div class="text-center mb-3">
                            <span class="text-3xl font-bold text-navy">Rp 25K</span>
                        </div>
                        <p class="text-gray-600 mb-6 text-center text-sm">Prioritas pengerjaan untuk deadline ketat</p>
                        <button onclick="showGuideModal('Edit Reels / Video Pendek', 'Fast Track')" class="btn-navy w-full py-3 rounded-lg font-semibold hover:scale-105 transition-transform">
                            Pesan Sekarang
                        </button>
                    </div>

                    <div class="card-service bg-white rounded-xl shadow-lg p-6 border-2 border-orange hover:shadow-2xl transition-shadow relative">
                        <div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse">
                            ⚡ FAST
                        </div>
                        <div class="navy text-white w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-navy mb-2 text-center">Cinematic</h4>
                        <div class="text-center mb-3">
                            <span class="text-3xl font-bold text-navy">Rp 30K</span>
                        </div>
                        <p class="text-gray-600 mb-6 text-center text-sm">Kualitas premium dengan waktu lebih cepat</p>
                        <button onclick="showGuideModal('Cinematic', 'Fast Track')" class="btn-navy w-full py-3 rounded-lg font-semibold hover:scale-105 transition-transform">
                            Pesan Sekarang
                        </button>
                    </div>

                    <div class="card-service bg-white rounded-xl shadow-lg p-6 border-2 border-orange hover:shadow-2xl transition-shadow relative">
                        <div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse">
                            ⚡ FAST
                        </div>
                        <div class="navy text-white w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-navy mb-2 text-center">Dokumenter</h4>
                        <div class="text-center mb-3">
                            <span class="text-3xl font-bold text-navy">Rp 80K</span>
                        </div>
                        <p class="text-gray-600 mb-6 text-center text-sm">Dokumentasi berkualitas dengan timeline singkat</p>
                        <button onclick="showGuideModal('Dokumenter', 'Fast Track')" class="btn-navy w-full py-3 rounded-lg font-semibold hover:scale-105 transition-transform">
                            Pesan Sekarang
                        </button>
                    </div>

                    <div class="card-service bg-white rounded-xl shadow-lg p-6 border-2 border-orange hover:shadow-2xl transition-shadow relative">
                        <div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse">
                            ⚡ FAST
                        </div>
                        <div class="navy text-white w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-navy mb-2 text-center">Preset</h4>
                        <div class="text-center mb-3">
                            <span class="text-3xl font-bold text-navy">Rp 30K</span>
                        </div>
                        <p class="text-gray-600 mb-6 text-center text-sm">Dapatkan preset custom lebih cepat</p>
                        <button onclick="showGuideModal('Preset', 'Fast Track')" class="btn-navy w-full py-3 rounded-lg font-semibold hover:scale-105 transition-transform">
                            Pesan Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- About Us Section -->
    <section id="about" class="py-24 px-6 relative overflow-hidden" style="background: linear-gradient(135deg, #0a0e27 0%, #1e3a8a 50%, #3b82f6 100%);">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-orange rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-400 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>
        
        <div class="container mx-auto relative z-10">
            <!-- Header -->
            <div class="text-center mb-20">
                <div class="inline-block mb-6">
                    <span class="text-orange font-bold text-lg tracking-widest uppercase">About Us</span>
                </div>
                <h2 class="text-6xl md:text-7xl font-extrabold text-white mb-6 leading-tight">
                    Welcome to <span class="bg-gradient-to-r from-orange-400 to-orange-600 bg-clip-text text-transparent">FrameKlip</span>
                </h2>
                <div class="w-40 h-2 mx-auto mb-8 rounded-full" style="background: linear-gradient(90deg, #f97316 0%, #fb923c 100%);"></div>
                <p class="text-2xl md:text-3xl text-white font-light max-w-4xl mx-auto leading-relaxed">
                    Your Trusted Partner for Professional Video Editing
                </p>
            </div>
            
            <div class="max-w-7xl mx-auto">
                <!-- Stats Section -->
                <div class="grid md:grid-cols-3 gap-6 mb-16">
                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-2xl rounded-2xl p-8 border border-white/20 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="text-6xl font-bold text-orange mb-2">500+</div>
                        <p class="text-white text-lg font-semibold">Videos Delivered</p>
                    </div>
                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-2xl rounded-2xl p-8 border border-white/20 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="text-6xl font-bold text-orange mb-2">200+</div>
                        <p class="text-white text-lg font-semibold">Happy Clients</p>
                    </div>
                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-2xl rounded-2xl p-8 border border-white/20 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="text-6xl font-bold text-orange mb-2">98%</div>
                        <p class="text-white text-lg font-semibold">Satisfaction Rate</p>
                    </div>
                </div>
                
                <!-- Main Description -->
                <div class="bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-2xl rounded-3xl p-12 md:p-16 mb-16 border border-white/30 shadow-2xl">
                    <p class="text-white text-xl md:text-2xl leading-relaxed text-center mb-8 font-light">
                        FrameKlip hadir untuk membantu para content creator menghasilkan video berkualitas tinggi dengan cepat dan terjangkau. Dengan tim editor berpengalaman dan passionate, kami siap mewujudkan visi kreatif Anda menjadi karya visual yang memukau.
                    </p>
                    <p class="text-white text-xl md:text-2xl leading-relaxed text-center font-light">
                        Dari video pendek untuk media sosial hingga dokumenter berkualitas cinema, kami menangani setiap proyek dengan detail dan dedikasi penuh.
                    </p>
                </div>
                
                <!-- Features Grid -->
                <div class="grid md:grid-cols-3 gap-8 mb-12">
                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-2xl rounded-3xl p-10 border border-white/20 hover:border-orange/50 hover:shadow-2xl hover:shadow-orange/20 transition-all duration-500 text-center group">
                        <div class="relative mb-8">
                            <div class="w-32 h-32 mx-auto rounded-full flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500" style="background: linear-gradient(135deg, #f97316 0%, #fb923c 100%); box-shadow: 0 20px 60px rgba(249, 115, 22, 0.4);">
                                <span class="text-6xl">✨</span>
                            </div>
                        </div>
                        <h4 class="font-bold text-white text-3xl mb-5">Premium Quality</h4>
                        <p class="text-white/90 text-lg leading-relaxed">
                            Editor profesional dengan pengalaman bertahun-tahun dan portfolio ratusan video berkualitas tinggi
                        </p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-2xl rounded-3xl p-10 border border-white/20 hover:border-orange/50 hover:shadow-2xl hover:shadow-orange/20 transition-all duration-500 text-center group">
                        <div class="relative mb-8">
                            <div class="w-32 h-32 mx-auto rounded-full flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500" style="background: linear-gradient(135deg, #f97316 0%, #fb923c 100%); box-shadow: 0 20px 60px rgba(249, 115, 22, 0.4);">
                                <span class="text-6xl">🚀</span>
                            </div>
                        </div>
                        <h4 class="font-bold text-white text-3xl mb-5">Lightning Fast</h4>
                        <p class="text-white/90 text-lg leading-relaxed">
                            Pilihan paket Regular (3-4 hari) atau Fast Track (1-2 hari) sesuai kebutuhan deadline Anda
                        </p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-2xl rounded-3xl p-10 border border-white/20 hover:border-orange/50 hover:shadow-2xl hover:shadow-orange/20 transition-all duration-500 text-center group">
                        <div class="relative mb-8">
                            <div class="w-32 h-32 mx-auto rounded-full flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500" style="background: linear-gradient(135deg, #f97316 0%, #fb923c 100%); box-shadow: 0 20px 60px rgba(249, 115, 22, 0.4);">
                                <span class="text-6xl">💎</span>
                            </div>
                        </div>
                        <h4 class="font-bold text-white text-3xl mb-5">Best Value</h4>
                        <p class="text-white/90 text-lg leading-relaxed">
                            Mulai dari Rp 15.000 per video dengan kualitas setara professional studio editing
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 px-6 bg-gray-100">
        <div class="container mx-auto">
            <h2 class="text-4xl font-bold text-center text-navy mb-12">Contact Us</h2>
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="space-y-6">
                        <div class="flex items-center space-x-4">
                            <div class="orange text-white w-12 h-12 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-navy">Email</h3>
                                <a href="mailto:info@frameklip.com" class="text-gray-600 hover:text-orange transition">admin@frameklip.my.id</a>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="orange text-white w-12 h-12 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-navy">WhatsApp</h3>
                                <a href="https://wa.me/<?php echo WA_NUMBER; ?>" class="text-gray-600 hover:text-orange transition">+62 813-6898-5901</a>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="orange text-white w-12 h-12 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-navy">Jam Operasional</h3>
                                <p class="text-gray-600">Senin - Minggu: 09.00 - 20.00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/<?php echo WA_NUMBER; ?>?text=Halo%20FrameKlip!%20Saya%20mau%20tanya%20tentang%20layanan%20editing%20video" 
       target="_blank"
       class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition-all duration-300 z-50 animate-bounce"
       title="Chat via WhatsApp">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
    </a>

    <!-- Footer -->
    <footer class="navy py-8 px-6">
        <div class="container mx-auto text-center">
            <img src="logo.png" alt="FrameKlip Logo" class="h-20 w-20 mx-auto mb-3 object-cover rounded-full" style="background: transparent; padding: 2px;">
            <h3 class="text-2xl font-bold text-white mb-2">Frame<span class="text-orange">Klip</span></h3>
            <p class="text-gray-300">&copy; 2024 FrameKlip. All rights reserved.</p>
        </div>
    </footer>

    <!-- Guide Modal -->
    <div id="guideModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-navy">📋 Cara Pemesanan</h3>
                <button onclick="closeGuideModal()" class="text-gray-500 hover:text-gray-700 text-3xl">&times;</button>
            </div>
            
            <div class="space-y-4 mb-6">
                <div class="flex items-start space-x-3 p-4 bg-orange-50 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 text-white rounded-full flex items-center justify-center font-bold text-lg" style="background-color: #f97316;">1</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-navy mb-1">Pilih Layanan & Paket</h4>
                        <p class="text-sm text-gray-700">Pilih jenis editing dan paket (Regular 3-4 hari atau Fast Track 1-2 hari)</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 text-white rounded-full flex items-center justify-center font-bold text-lg" style="background-color: #f97316;">2</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-navy mb-1">Isi Form Pemesanan</h4>
                        <p class="text-sm text-gray-700">Lengkapi data: Nama, Email, No. WhatsApp</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3 p-4 bg-green-50 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 text-white rounded-full flex items-center justify-center font-bold text-lg" style="background-color: #f97316;">3</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-navy mb-1">Transfer Pembayaran</h4>
                        <p class="text-sm text-gray-700">Transfer sesuai nominal ke rekening yang ditampilkan</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3 p-4 bg-purple-50 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 text-white rounded-full flex items-center justify-center font-bold text-lg" style="background-color: #f97316;">4</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-navy mb-1">Kirim Bukti via WhatsApp</h4>
                        <p class="text-sm text-gray-700">Kirim foto bukti transfer dan link Google Drive</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3 p-4 bg-yellow-50 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 text-white rounded-full flex items-center justify-center font-bold text-lg" style="background-color: #f97316;">5</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-navy mb-1">Tunggu Konfirmasi & Hasil</h4>
                        <p class="text-sm text-gray-700">Video selesai dikirim via WhatsApp sesuai estimasi</p>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button onclick="closeGuideModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 rounded-lg transition">
                    ← Kembali
                </button>
                <button onclick="proceedToOrderForm()" class="flex-1 text-white font-bold py-3 rounded-lg transition hover:scale-105" style="background-color: #f97316;">
                    Lanjut Pesan →
                </button>
            </div>
        </div>
    </div>

    <!-- Order Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-navy">Form Pemesanan</h3>
                <button onclick="closeOrderModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="orderForm" onsubmit="handleOrderSubmit(event)">
                <div class="mb-4">
                    <label class="block text-navy font-semibold mb-2">Layanan yang dipilih:</label>
                    <p id="selectedService" class="text-gray-700 font-medium"></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-navy font-semibold mb-2">Paket:</label>
                    <p id="selectedPackage" class="text-gray-700 font-medium"></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-navy font-semibold mb-2">Nama Lengkap *</label>
                    <input type="text" id="customerName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-orange">
                </div>
                
                <div class="mb-4">
                    <label class="block text-navy font-semibold mb-2">Email *</label>
                    <input type="email" id="customerEmail" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-orange">
                </div>
                
                <div class="mb-4">
                    <label class="block text-navy font-semibold mb-2">No. Telepon (WhatsApp) *</label>
                    <input type="tel" id="customerPhone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-orange" placeholder="contoh: 08123456789">
                </div>
                
                <div class="mb-6">
                    <label class="block text-navy font-semibold mb-2">Catatan (Optional)</label>
                    <textarea id="customerNotes" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-orange" placeholder="Deskripsikan kebutuhan video Anda..."></textarea>
                </div>
                
                <div id="errorMessage" class="hidden mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded"></div>
                
                <button type="submit" id="submitBtn" class="btn-orange w-full py-3 rounded-lg font-semibold text-lg">
                    Lanjut ke Pembayaran
                </button>
            </form>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-navy">Informasi Pembayaran</h3>
                <button onclick="closePaymentModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Order ID -->
            <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4 mb-6">
                <p class="text-center text-green-700">
                    ✅ <strong>Pesanan berhasil disimpan!</strong><br>
                    <span class="font-bold text-lg">Order ID: #<span id="orderId"></span></span>
                </p>
            </div>
            
            <!-- Ringkasan Pembelian -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h4 class="text-lg font-bold text-navy mb-4">Ringkasan Pemesanan</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Layanan:</span>
                        <span id="summaryService" class="font-semibold text-navy"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Paket:</span>
                        <span id="summaryPackage" class="font-semibold text-navy"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estimasi Selesai:</span>
                        <span id="summaryEstimate" class="font-semibold text-blue-600"></span>
                    </div>
                    <div class="flex justify-between border-t-2 border-gray-200 pt-3 mt-3">
                        <span class="text-gray-600 font-bold text-lg">Total Bayar:</span>
                        <span id="summaryPrice" class="font-bold text-orange text-2xl"></span>
                    </div>
                    <div class="flex justify-between mt-4 pt-4 border-t border-gray-200">
                        <span class="text-gray-600">Nama:</span>
                        <span id="summaryName" class="font-semibold text-navy"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span id="summaryEmail" class="font-semibold text-navy"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">No. HP:</span>
                        <span id="summaryPhone" class="font-semibold text-navy"></span>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Rekening -->
            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
                <h4 class="text-lg font-bold text-navy mb-4">Transfer ke Rekening:</h4>
                <div class="flex items-center space-x-4 mb-4">
                    <div class="bg-white px-4 py-2 rounded">
                        <div class="text-blue-600 font-bold text-xl"><?php echo BANK_NAME; ?></div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor Rekening</p>
                        <p class="text-2xl font-bold text-navy"><?php echo BANK_ACCOUNT; ?></p>
                        <p class="text-sm text-gray-600">a.n. <?php echo BANK_HOLDER; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Instruksi -->
            <div class="bg-orange-50 border-2 border-orange-200 rounded-lg p-6 mb-6">
                <h4 class="text-lg font-bold text-navy mb-3">Langkah Selanjutnya:</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Transfer sesuai nominal yang telah disepakati</li>
                    <li>Kirim bukti transfer ke WhatsApp kami</li>
                    <li>Upload file video Anda ke Google Drive</li>
                    <li>Kirim link Google Drive ke WhatsApp kami</li>
                    <li>Tim kami akan segera memproses pesanan Anda!</li>
                </ol>
            </div>
            
            <!-- Auto-Redirect Info -->
            <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4 mb-6 text-center">
                <p class="text-green-700 font-semibold mb-2">
                    🚀 Akan otomatis membuka WhatsApp dalam <span id="countdown" class="text-2xl font-bold">5</span> detik...
                </p>
                <p class="text-sm text-gray-600">Atau klik tombol di bawah untuk langsung kirim sekarang</p>
            </div>
            
            <!-- Tombol WhatsApp -->
            <a id="whatsappBtn" href="#" target="_blank" class="btn-navy w-full py-4 rounded-lg font-semibold text-lg flex items-center justify-center space-x-2 mb-3">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                <span>📱 Kirim Bukti & Link GDrive Sekarang</span>
            </a>
            
            <button onclick="cancelAutoRedirect()" class="w-full text-gray-600 hover:text-gray-800 py-2 text-sm underline">
                Batal otomatis redirect
            </button>
        </div>
    </div>

</body>
</html>