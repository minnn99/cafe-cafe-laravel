// CafeCafe JavaScript

document.addEventListener("DOMContentLoaded", function () {
  // モバイルメニューの処理
  const hamburger = document.querySelector(".hamburger");
  const navMenu = document.querySelector(".nav-menu");

  if (hamburger) {
    hamburger.addEventListener("click", function () {
      navMenu.classList.toggle("active");
    });
  }

  // スムーススクロール
  const links = document.querySelectorAll('a[href^="#"]');
  links.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
        });
      }
    });
  });

  // カードのホバーエフェクト
  const cards = document.querySelectorAll(".location-card, .intro-card, .experience-card, .host-card");
  cards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-5px)";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)";
    });
  });

  // ページトップボタン
  const scrollToTop = document.createElement("button");
  scrollToTop.innerHTML = "Jump to Top";
  scrollToTop.className = "scroll-to-top";
  scrollToTop.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #111;
        color: white;
        border: none;
        font-size: 1rem;
        height: 50px;
        width: 120px;
        border-radius: 25px;
        cursor: pointer;
        z-index: 1000;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    `;

  // ホバー効果追加
  scrollToTop.addEventListener("mouseenter", function () {
    this.style.background = "#333";
    this.style.transform = this.style.transform.replace("translateY(0px)", "translateY(-5px)");
  });

  scrollToTop.addEventListener("mouseleave", function () {
    this.style.background = "#111";
    this.style.transform = this.style.transform.replace("translateY(-5px)", "translateY(0px)");
  });

  document.body.appendChild(scrollToTop);

  // ヘッダーとアラートのスクロール処理
  const header = document.querySelector(".header");
  const alert = document.querySelector(".alert");
  const mainContent = document.querySelector(".main-content");
  window.addEventListener("scroll", function () {
    const scrollTop = window.pageYOffset;

    // スクロール位置に応じてヘッダーとアラートの表示を制御
    if (scrollTop <= 10) {
      // 最上部にいる場合（10px以内）
      alert.classList.add("show");
      header.classList.remove("scrolled");
      header.classList.add("with-alert");
      // 明示的に透明背景を設定
      header.style.background = "rgba(0, 0, 0, 0)";
    } else {
      // スクロールした場合
      alert.classList.remove("show");
      header.classList.add("scrolled");
      header.classList.remove("with-alert");
      // インラインスタイルを削除してCSSクラスが適用されるように
      header.style.background = "";
    }

    // ページトップボタンの表示制御 (애니메이션 개선)
    if (scrollTop > 300) {
      scrollToTop.style.transform = "translateY(0px)";
      scrollToTop.style.opacity = "1";
    } else {
      scrollToTop.style.transform = "translateY(100px)";
      scrollToTop.style.opacity = "0";
    }
  });

  // 初期状態でアラートを表示
  if (window.pageYOffset <= 10) {
    alert.classList.add("show");
    header.classList.add("with-alert");
    // 初期状態で透明背景を設定
    header.style.background = "rgba(0, 0, 0, 0)";
  }

  scrollToTop.addEventListener("click", function () {
    // 클릭 애니메이션 효과
    this.style.transform = this.style.transform.replace("translateY(0px)", "translateY(-10px)");
    setTimeout(() => {
      this.style.transform = this.style.transform.replace("translateY(-10px)", "translateY(0px)");
    }, 100);

    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });
});
