<footer class="modern-footer">
    <div class="footer-content">
        <div class="footer-links">
            <a class="footer-link" href="index?page=faq">
                <span>FAQ</span>
            </a>
            <span class="separator">•</span>
            <a class="footer-link" href="index?page=cgu">
                <span>CGU</span>
            </a>
            <span class="separator">•</span>
            <a class="footer-link" href="contact">
                <span>Contact</span>
            </a>
        </div>
        
        <div class="footer-copyright">
            <span class="gradient-text">© <?php echo date("Y"); ?> Logemangue</span>
            <span class="rights">Tous droits réservés</span>
        </div>
        
        <div class="footer-update">
            <small>Dernière mise à jour : <?php echo date("d/m/Y à H:i"); ?></small>
        </div>
    </div>
    
    <div class="footer-gradient"></div>
</footer>

<style>
.modern-footer {
    position: relative;
    background: #ffffff;
    padding: 3rem 2rem 2rem;
    margin-top: 4rem;
    overflow: hidden;
    border-top: 1px solid #f0f0f0;
}

.footer-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #ffd700 0%, #ff8c00 50%, #ff6b35 100%);
    box-shadow: 0 0 20px rgba(255, 140, 0, 0.5);
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}

.footer-links {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
    justify-content: center;
}

.footer-link {
    color: #333;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 500;
    position: relative;
    transition: all 0.3s ease;
    padding: 0.5rem 0;
}

.footer-link span {
    position: relative;
    z-index: 1;
}

.footer-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #ffd700, #ff8c00);
    transition: width 0.3s ease;
}

.footer-link:hover {
    color: #ff8c00;
    transform: translateY(-2px);
}

.footer-link:hover::after {
    width: 100%;
}

.separator {
    color: #ccc;
    font-size: 0.8rem;
}

.footer-copyright {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: center;
    font-size: 0.95rem;
}

.gradient-text {
    background: linear-gradient(90deg, #ffd700 0%, #ff8c00 50%, #ff6b35 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 600;
    font-size: 1.1rem;
}

.rights {
    color: #666;
}

.footer-update {
    color: #999;
    font-size: 0.85rem;
    text-align: center;
}

.footer-update small {
    opacity: 0.8;
}

@media (max-width: 768px) {
    .modern-footer {
        padding: 2.5rem 1.5rem 1.5rem;
    }
    
    .footer-links {
        gap: 1rem;
    }
    
    .footer-link {
        font-size: 0.9rem;
    }
    
    .footer-copyright {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .gradient-text {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .footer-links {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .separator {
        display: none;
    }
}
</style>

<script src="../js/responsive.js"></script>