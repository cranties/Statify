<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
  <defs>
    <linearGradient id="sg-grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#6366f1;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="sg-pulse" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#06b6d4;stop-opacity:1" />
    </linearGradient>
    <filter id="sg-glow" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur stdDeviation="4" result="blur" />
      <feComposite in="SourceGraphic" in2="blur" operator="over" />
    </filter>
  </defs>

  <!-- Background rounded square -->
  <rect x="10" y="10" width="180" height="180" rx="36" ry="36" fill="url(#sg-grad)" />

  <!-- Server rack body -->
  <rect x="48" y="52" width="104" height="88" rx="6" ry="6" fill="rgba(255,255,255,0.12)" stroke="rgba(255,255,255,0.3)" stroke-width="1.5" />

  <!-- Server unit 1 -->
  <rect x="56" y="60" width="88" height="20" rx="4" ry="4" fill="rgba(255,255,255,0.18)" />
  <circle cx="72" cy="70" r="4" fill="#10b981" />
  <rect x="82" y="66" width="36" height="3" rx="1.5" fill="rgba(255,255,255,0.3)" />
  <rect x="82" y="71" width="22" height="3" rx="1.5" fill="rgba(255,255,255,0.2)" />
  <rect x="128" y="63" width="10" height="14" rx="2" fill="rgba(255,255,255,0.15)" />

  <!-- Server unit 2 -->
  <rect x="56" y="86" width="88" height="20" rx="4" ry="4" fill="rgba(255,255,255,0.18)" />
  <circle cx="72" cy="96" r="4" fill="#10b981" />
  <rect x="82" y="92" width="30" height="3" rx="1.5" fill="rgba(255,255,255,0.3)" />
  <rect x="82" y="97" width="44" height="3" rx="1.5" fill="rgba(255,255,255,0.2)" />
  <rect x="128" y="89" width="10" height="14" rx="2" fill="rgba(255,255,255,0.15)" />

  <!-- Server unit 3 (warning/down) -->
  <rect x="56" y="112" width="88" height="20" rx="4" ry="4" fill="rgba(255,255,255,0.18)" />
  <circle cx="72" cy="122" r="4" fill="#ef4444" />
  <rect x="82" y="118" width="40" height="3" rx="1.5" fill="rgba(255,255,255,0.3)" />
  <rect x="82" y="123" width="26" height="3" rx="1.5" fill="rgba(255,255,255,0.2)" />
  <rect x="128" y="115" width="10" height="14" rx="2" fill="rgba(255,255,255,0.15)" />

  <!-- Signal/Pulse wave beneath rack -->
  <polyline
    points="48,155 62,155 68,143 76,168 84,148 92,155 104,155 112,143 120,162 128,150 136,155 152,155"
    fill="none"
    stroke="url(#sg-pulse)"
    stroke-width="2.5"
    stroke-linecap="round"
    stroke-linejoin="round"
    filter="url(#sg-glow)"
  />
</svg>
