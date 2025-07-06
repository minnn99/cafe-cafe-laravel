// CafeCafe JavaScript - リファクタリング済み

class CafeCafeApp {
  constructor() {
    this.elements = {
      hamburger: document.querySelector(".hamburger"),
      hamburgerMenu: document.querySelector(".hamburger-menu"),
      header: document.querySelector(".header"),
      alert: document.querySelector(".alert"),
      mainContent: document.querySelector(".main-content"),
    };

    this.scrollToTopButton = null;
    this.isScrolling = false;

    this.init();
  }

  init() {
    this.initMobileMenu(); // モバイルメニューの初期化
    this.initSmoothScroll(); // スムーズスクロールの初期化

    // contactページでない場合のみスクロールトップボタンを作成
    if (!this.isContactPage()) {
      this.createScrollToTopButton(); // スクロールトップボタンの作成
    }

    this.initScrollHandler(); // スクロールハンドラーの初期化
    this.setInitialState(); // 初期状態の設定
  }

  // contactページかどうかを判定するメソッド
  isContactPage() {
    return window.location.pathname.includes("contact.php") || window.location.pathname.endsWith("contact");
  }

  // モバイルメニューの初期化
  initMobileMenu() {
    const { hamburger, hamburgerMenu } = this.elements;

    if (!hamburger || !hamburgerMenu) return;

    hamburger.addEventListener("click", (e) => {
      e.stopPropagation();
      hamburgerMenu.classList.toggle("active");
    });

    // メニュー外をクリックした場合にメニューを閉じる
    document.addEventListener("click", (e) => {
      if (!hamburger.contains(e.target)) {
        hamburgerMenu.classList.remove("active");
      }
    });
  }

  // スムーズスクロールの初期化
  initSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');

    links.forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        const targetId = link.getAttribute("href");
        const target = document.querySelector(targetId);

        if (target) {
          target.scrollIntoView({
            behavior: "smooth",
            block: "start",
          });
        }
      });
    });
  }

  // スクロールトップボタンの作成
  createScrollToTopButton() {
    this.scrollToTopButton = document.createElement("button");
    this.scrollToTopButton.innerHTML = "Jump to Top";
    this.scrollToTopButton.className = "scroll-to-top";
    this.scrollToTopButton.setAttribute("aria-label", "ページの上部に戻る");

    // ホバー効果
    this.addScrollButtonHoverEffects();

    // クリックイベント
    this.scrollToTopButton.addEventListener("click", () => {
      this.animateScrollToTop();
    });

    document.body.appendChild(this.scrollToTopButton);
  }

  // スクロールボタンのホバー効果
  addScrollButtonHoverEffects() {
    // ホバー効果はCSSで処理しているため、JavaScriptでのホバーイベントは不要
    // 必要に応じて追加のホバーロジックをここに実装可能
  }

  // スクロールトップアニメーション
  animateScrollToTop() {
    // クリックアニメーション効果（CSSベース）
    this.scrollToTopButton.classList.add("clicked");

    setTimeout(() => {
      this.scrollToTopButton.classList.remove("clicked");
    }, 200);

    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  }

  // スクロールハンドラーの初期化
  initScrollHandler() {
    let ticking = false;

    window.addEventListener("scroll", () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          this.handleScroll();
          ticking = false;
        });
        ticking = true;
      }
    });
  }

  // スクロール処理
  handleScroll() {
    const scrollTop = window.pageYOffset;

    this.updateHeaderState(scrollTop); // ヘッダーの状態を更新
    this.updateScrollToTopButton(scrollTop); // スクロールトップボタンの表示制御
  }

  // ヘッダーの状態を更新
  updateHeaderState(scrollTop) {
    const { header, alert } = this.elements;

    if (!header || !alert) return;

    if (scrollTop <= 10) {
      // 最上部にいる場合
      alert.classList.add("show");
      header.classList.remove("scrolled");
      header.classList.add("with-alert");
      header.style.background = "rgba(0, 0, 0, 0)";
    } else {
      // スクロールした場合
      alert.classList.remove("show");
      header.classList.add("scrolled");
      header.classList.remove("with-alert");
      header.style.background = "";
    }
  }

  // スクロールトップボタンの表示制御
  updateScrollToTopButton(scrollTop) {
    if (!this.scrollToTopButton) return;

    if (scrollTop > 300) {
      this.scrollToTopButton.classList.add("visible");
    } else {
      this.scrollToTopButton.classList.remove("visible");
    }
  }

  // 初期状態の設定
  setInitialState() {
    const { header, alert } = this.elements;

    if (window.pageYOffset <= 10 && header && alert) {
      alert.classList.add("show");
      header.classList.add("with-alert");
      header.style.background = "rgba(0, 0, 0, 0)";
    }
  }
}

// アプリケーションの初期化
document.addEventListener("DOMContentLoaded", () => {
  new CafeCafeApp();
});
