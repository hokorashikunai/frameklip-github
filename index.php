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

</body>
</html>