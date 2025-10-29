@extends('layouts.dashboard')
@section('content')

<div class="p-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">✅ Test de la Sidebar Moderne</h1>

        <div class="space-y-4">
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <h3 class="font-semibold text-green-800">Sidebar Organisée :</h3>
                <ul class="mt-2 text-sm text-green-700 list-disc list-inside">
                    <li>🏠 لوحة التحكم (Dashboard) - Non groupé</li>
                    <li>👥 المؤثرين (Influenceurs) - Non groupé</li>
                    <li>🧰 الخدمات (Services) - Non groupé</li>
                    <li>💼 الأعمال والخدمات - Section groupée collapsible</li>
                    <li>💬 التواصل والدعم - Section groupée collapsible</li>
                    <li>💰 الحساب والمعاملات - Section groupée collapsible</li>
                </ul>
            </div>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-semibold text-blue-800">Fonctionnalités :</h3>
                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                    <li>Sections collapsibles avec Alpine.js</li>
                    <li>Animation fluide</li>
                    <li>État actif intelligent</li>
                    <li>Support RTL pour l'arabe</li>
                    <li>Design responsive</li>
                </ul>
            </div>

            <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                <h3 class="font-semibold text-purple-800">Routes Corrigées :</h3>
                <ul class="mt-2 text-sm text-purple-700 list-disc list-inside">
                    <li>/client/dashboard - Dashboard principal</li>
                    <li>/getinf - Page des influenceurs (corrigé)</li>
                    <li>/services - Page des services (modernisée)</li>
                    <li>/client/campaign - Gestion des campagnes</li>
                </ul>
            </div>
        </div>

        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-yellow-800 text-sm">
                <strong>Note :</strong> Si vous voyez encore l'ancienne sidebar, veuillez vider le cache de votre navigateur (Ctrl+F5 ou Cmd+Shift+R).
            </p>
        </div>
    </div>
</div>

@endsection