@extends('layouts.app')

@section('title', '403 - Acceso Denegado')

@section('content')
<style>
  /* Fondo suave */
.err-bg{
    min-height: calc(100vh - 120px);
    background: radial-gradient(circle at 20% 20%, rgba(99,102,241,.12), transparent 45%),
                radial-gradient(circle at 80% 30%, rgba(239,68,68,.12), transparent 50%),
                linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px 16px;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

  /* Card */
.err-card{
    width: min(960px, 100%);
    background: rgba(255,255,255,.92);
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 20px;
    box-shadow: 0 24px 60px rgba(15,23,42,.12);
    overflow: hidden;
    animation: popIn .5s ease-out;
    z-index: 2;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px;
    margin: 0 auto;
}

@keyframes popIn{
    from{ transform: translateY(10px); opacity: 0; }
    to{ transform: translateY(0); opacity: 1; }
}

  /* Layout interno */
.err-grid{
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 0;
    align-items: center;
    justify-content: center;
}

@media (max-width: 900px){
    .err-grid{ grid-template-columns: 1fr; }
    .err-left, .err-right{ padding: 24px; }

.err-left{
    padding: 28px;
    display:flex;
    align-items:center;
    justify-content:center;
    background: linear-gradient(135deg, rgba(99,102,241,.08), rgba(239,68,68,.08));
    border-radius: 14px;
    box-shadow: 0 18px 40px rgba(15,23,42,.18);
    transform: rotate(2deg);
    transition: transform .25s ease;
}
.err-left:hover{ transform: rotate(0deg) scale(1.01); }
}

.err-img{
    width: 100%;
    max-width: 520px;
    border-radius: 14px;
    box-shadow: 0 18px 40px rgba(15,23,42,.18);
    transform: rotate(-2deg);
    transition: transform .25s ease;
}
.err-img:hover{ transform: rotate(0deg) scale(1.01); }

.err-right{
    padding: 34px 34px 30px 34px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    gap: 16px;
}

.err-badge{
    display:inline-flex;
    align-items:center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 700;
    font-size: .9rem;
    color: #991b1b;
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.25);
    width: fit-content;
    margin-bottom: 14px;
}

.err-dot{
    width: 10px; height: 10px; border-radius: 999px;
    background: #ef4444;
    box-shadow: 0 0 0 6px rgba(239,68,68,.15);
    animation: pulse 1.6s infinite; 
    transition: box-shadow .15s ease;
}

@keyframes pulse{
    0%{ box-shadow: 0 0 0 0 rgba(239,68,68,.25); }
    70%{ box-shadow: 0 0 0 12px rgba(239,68,68,0); }
    100%{ box-shadow: 0 0 0 0 rgba(239,68,68,0); }
}

.err-title{
    font-size: clamp(28px, 4vw, 40px);
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px 0;
    letter-spacing: -0.02em;
    text-transform: uppercase;
    text-align: center;
}

.err-sub{
    color: #475569;
    font-size: 1.02rem;
    margin-bottom: 18px;
    line-height: 1.5;
        text-align: center;
}

.err-actions{
    display:flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 8px;
        justify-content: center;
        align-items: center;
}

.err-actions{
    display:flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 8px;
        justify-content: center;
        align-items: center;
        text-align: center;
}

.btn-main{
    display:inline-flex;
    align-items:center;
    gap: 10px;
    padding: 10px 16px;
    border-radius: 12px;
    border: 0;
    background: #4f46e5;
    color: #fff;
    font-weight: 700;
    box-shadow: 0 10px 20px rgba(79,70,229,.25);
    text-decoration: none;
    transition: transform .15s ease, box-shadow .15s ease;
    cursor: pointer;
    outline: none;
    text-align: center;
    width: fit-content;
    margin: 0 auto;
}

.btn-main:hover{
    transform: translateY(-1px);
    box-shadow: 0 14px 26px rgba(79,70,229,.30);
    color: #fff;
    background: #4f46e5;
}

@media (max-width: 900px){
    .btn-main{ width: 100%; justify-content: center; }
    .btn-main:hover{ transform: translateY(0); }
}

.btn-ghost{
    display:inline-flex;
    align-items:center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 12px;
    background: rgba(15,23,42,.04);
    border: 1px solid rgba(15,23,42,.08);
    color: #0f172a;
    font-weight: 700;
    text-decoration: none;
    transition: background .15s ease, color .15s ease;
    cursor: pointer;
    outline: none;
    text-align: center;
    width: fit-content;
    margin: 0 auto;
}

.btn-ghost:hover{ background: rgba(15,23,42,.06); color:#0f172a; }

.err-help{
    margin-top: 16px;
    font-size: .92rem;
    color: #64748b;
    text-align: center;
}

.err-code{
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    background: rgba(15,23,42,.05);
    border: 1px solid rgba(15,23,42,.08);
    padding: 2px 8px;
    border-radius: 8px;
    color: #0f172a;
    font-weight: 700;
    font-size: .9rem;
}

</style>

<div class="err-bg">
    <div class="err-card">
        <div class="err-grid">
            <div class="err-left">
                <img src="{{ asset('img/errors.jpg') }}" alt="Acceso Denegado" class="err-img">
            </div>

            <div class="err-right">
                <div class="err-badge">
                    <span class="err-dot"></span>
                    Acceso restringido
                </div>

                <h1 class="err-title">403 — Acceso denegado</h1>

                <p class="err-sub">
                    No tienes permisos para acceder a este módulo. Si crees que esto es un error,
                    solicita al administrador que te asigne el permiso correspondiente.
                </p>

                <div class="err-actions">
                    <a class="btn-main" href="{{ route('home') }}">
                        <!-- Icono simple SVG -->
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M10 20v-6h4v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-4v-7H9v7H5a1 1 0 0 1-1-1v-9.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Ir al inicio
                    </a>

                    <a class="btn-ghost" href="javascript:history.back()">
                        <!-- Icono simple SVG -->
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Volver
                    </a>
                </div>

                <div class="err-help">
                Código de error: <span class="err-code">HTTP 403</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection