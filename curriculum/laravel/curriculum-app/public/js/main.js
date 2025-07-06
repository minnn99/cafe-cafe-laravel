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

    this.initFormValidation(); // フォームバリデーションの初期化
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

  // フォームバリデーションの初期化
  initFormValidation() {
    const form = document.querySelector(".contact-form");
    if (!form) return;

    // リアルタイムバリデーション
    const inputs = form.querySelectorAll("input, textarea");
    inputs.forEach((input) => {
      input.addEventListener("blur", () => this.validateField(input));
      input.addEventListener("input", () => this.clearError(input));
    });

    // フォーム送信時のバリデーション
    form.addEventListener("submit", (e) => {
      if (!this.validateForm(form)) {
        e.preventDefault();
      }
    });
  }

  // 個別フィールドのバリデーション
  validateField(field) {
    const fieldName = field.name;
    const value = field.value.trim();
    let errorMessage = "";

    switch (fieldName) {
      case "name":
        if (!value) {
          errorMessage = "氏名は必須入力です。10文字以内で入力してください。";
        } else if (value.length > 10) {
          errorMessage = "氏名は10文字以内で入力してください。";
        }
        break;

      case "furigana":
        if (!value) {
          errorMessage = "フリガナは必須入力です。10文字以内で入力してください。";
        } else if (value.length > 10) {
          errorMessage = "フリガナは10文字以内で入力してください。";
        } else if (!/^[ァ-ヶー]+$/.test(value)) {
          errorMessage = "フリガナはカタカナで入力してください。";
        }
        break;

      case "phone":
        if (value && !/^[\d-]+$/.test(value)) {
          errorMessage = "電話番号は0-9の数字のみで入力してください。";
        }
        break;

      case "email":
        if (!value) {
          errorMessage = "メールアドレスは正しく入力してください。";
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
          errorMessage = "メールアドレスは正しく入力してください。";
        }
        break;

      case "message":
        if (!value) {
          errorMessage = "お問い合わせ内容は必須入力です。";
        }
        break;
    }

    this.showError(field, errorMessage);
    return !errorMessage;
  }

  // エラー表示
  showError(field, message) {
    const errorElement = document.getElementById(field.name + "-error");
    if (errorElement) {
      errorElement.textContent = message;
      if (message) {
        errorElement.classList.add("show");
        field.classList.add("error");
      } else {
        errorElement.classList.remove("show");
        field.classList.remove("error");
      }
    }
  }

  // エラークリア
  clearError(field) {
    const errorElement = document.getElementById(field.name + "-error");
    if (errorElement && field.value.trim()) {
      errorElement.textContent = "";
      errorElement.classList.remove("show");
      field.classList.remove("error");
    }
  }

  // フォーム全体のバリデーション
  validateForm(form) {
    const requiredFields = form.querySelectorAll("[required]");
    let isValid = true;

    requiredFields.forEach((field) => {
      if (!this.validateField(field)) {
        isValid = false;
      }
    });

    return isValid;
  }
}

// アプリケーションの初期化
document.addEventListener("DOMContentLoaded", () => {
  new CafeCafeApp();
});
