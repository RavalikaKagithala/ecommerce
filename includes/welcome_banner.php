<?php
// Welcome Banner
echo '
<style>
.welcome-marquee {
    width: 100%;
    background: linear-gradient(90deg, #4e73df, #1cc88a);
    color: white;
    padding: 12px 0;
    overflow: hidden;
    position: relative;
    font-family: "Segoe UI", sans-serif;
}

.welcome-marquee p {
    display: inline-block;
    white-space: nowrap;
    animation: scrollText 18s linear infinite;
    font-weight: bold;
    font-size: 18px;
    padding-left: 100%;
}

.welcome-marquee:hover p {
    animation-play-state: paused;
}

@keyframes scrollText {
    0% { transform: translateX(0%); }
    100% { transform: translateX(-100%); }
}
</style>

<div class="welcome-marquee">
    <p>🛍 Welcome to Our Shopping Store! Big Discounts | Fast Delivery | Best Quality Products 🛒</p>
</div>
';
?>