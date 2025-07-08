// CafeCafe JavaScript - メイン機能管理クラス

class CafeCafeApp {
    constructor() {
        // DOM要素をまとめて管理
        this.elements = {
            hamburger: document.querySelector(".hamburger"),
            hamburgerMenu: document.querySelector(".hamburger-menu"),
            header: document.querySelector(".header"),
            alert: document.querySelector(".alert"),
            mainContent: document.querySelector(".main-content"),
        };

        this.scrollToTopButton = null;
        this.init();
    }

    // アプリケーションの初期化
    init() {
        this.initMobileMenu(); // モバイルメニューの初期化
        this.initSmoothScroll(); // スムーズスクロールの初期化

        // メインページ（home）でのみスクロールボタンを作成
        if (this.isHomePage()) {
            this.createScrollToTopButton(); // スクロールトップボタンの作成
        }

        this.initScrollHandler(); // スクロールイベントの初期化
        this.setInitialState(); // 初期状態の設定
        this.initFormValidation(); // フォームバリデーションの初期化
        this.initLoginModal(); // ログインモーダルの初期化
    }

    // ホームページかどうかを判定
    isHomePage() {
        const pathname = window.location.pathname;
        return (
            pathname === "/" ||
            pathname === "/home" ||
            pathname.endsWith("/") ||
            pathname === "" ||
            pathname === window.location.origin
        );
    }

    // contactページまたはconfirmページかどうかを判定（既存の関数は保持）
    isContactPage() {
        const pathname = window.location.pathname;
        return (
            pathname.includes("contact.php") ||
            pathname.includes("confirm.php") ||
            pathname.endsWith("contact") ||
            pathname.endsWith("confirm")
        );
    }

    // モバイルハンバーガーメニューの初期化
    initMobileMenu() {
        const { hamburger, hamburgerMenu } = this.elements;
        if (!hamburger || !hamburgerMenu) return;

        // ハンバーガーボタンクリック時の処理
        hamburger.addEventListener("click", (e) => {
            e.stopPropagation(); // イベントの伝播を防ぐ
            hamburgerMenu.classList.toggle("active");
        });

        // メニュー外をクリックした場合にメニューを閉じる
        document.addEventListener("click", (e) => {
            if (!hamburger.contains(e.target)) {
                hamburgerMenu.classList.remove("active");
            }
        });
    }

    // ページ内リンクのスムーズスクロール機能
    initSmoothScroll() {
        const hashLinks = document.querySelectorAll('a[href^="#"]');

        hashLinks.forEach((link) => {
            link.addEventListener("click", (e) => {
                e.preventDefault();
                const targetId = link.getAttribute("href");
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    });
                }
            });
        });
    }

    // ページトップに戻るボタンを作成
    createScrollToTopButton() {
        // ボタン要素を作成
        this.scrollToTopButton = document.createElement("button");
        this.scrollToTopButton.innerHTML = "Jump to Top";
        this.scrollToTopButton.className = "scroll-to-top";
        this.scrollToTopButton.setAttribute("aria-label", "ページの上部に戻る");

        // クリック時のイベント処理
        this.scrollToTopButton.addEventListener("click", () => {
            // クリックアニメーション効果を追加
            this.scrollToTopButton.classList.add("clicked");
            setTimeout(() => {
                this.scrollToTopButton.classList.remove("clicked");
            }, 200);

            // ページトップにスムーズスクロール
            window.scrollTo({ top: 0, behavior: "smooth" });
        });

        // ページにボタンを追加
        document.body.appendChild(this.scrollToTopButton);
    }

    // スクロールイベントハンドラーの初期化
    initScrollHandler() {
        let ticking = false; // パフォーマンス最適化用フラグ

        window.addEventListener("scroll", () => {
            // requestAnimationFrameを使用してスクロール処理を最適化
            if (!ticking) {
                requestAnimationFrame(() => {
                    this.handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
    }

    // スクロール時の処理を実行
    handleScroll() {
        const scrollTop = window.pageYOffset;
        this.updateHeaderState(scrollTop); // ヘッダーの状態を更新
        this.updateScrollToTopButton(scrollTop); // スクロールボタンの表示制御
    }

    // スクロール位置に応じてヘッダーの表示状態を更新
    updateHeaderState(scrollTop) {
        const { header, alert } = this.elements;
        if (!header || !alert) return;

        if (scrollTop <= 10) {
            // ページ最上部にいる場合
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

    // スクロールトップボタンの表示/非表示を制御
    updateScrollToTopButton(scrollTop) {
        if (!this.scrollToTopButton) return;

        // 300px以上スクロールした時にボタンを表示
        const shouldShow = scrollTop > 300;
        this.scrollToTopButton.classList.toggle("visible", shouldShow);
    }

    // ページ読み込み時の初期状態を設定
    setInitialState() {
        const { header, alert } = this.elements;

        // ページトップにいる場合の初期設定
        if (window.pageYOffset <= 10 && header && alert) {
            alert.classList.add("show");
            header.classList.add("with-alert");
            header.style.background = "rgba(0, 0, 0, 0)";
        }
    }

    // フォームバリデーション機能の初期化
    initFormValidation() {
        const contactForm = document.querySelector(".contact-form");
        if (!contactForm) return; // フォームが存在しない場合は処理を終了

        // 入力フィールドにイベントリスナーを設定
        const inputFields = contactForm.querySelectorAll("input, textarea");
        inputFields.forEach((input) => {
            // フォーカスが外れた時にバリデーション実行
            input.addEventListener("blur", () => this.validateField(input));
            // 入力中はエラーを削除
            input.addEventListener("input", () => this.clearError(input));
        });

        // フォーム送信時のバリデーション
        contactForm.addEventListener("submit", (e) => {
            const validationResult = this.validateFormWithAlert(contactForm);
            if (!validationResult.isValid) {
                e.preventDefault(); // 送信を中止
                alert(validationResult.errorMessage); // エラーメッセージを表示
            }
        });
    }

    // 個別フィールドのバリデーション処理
    validateField(field) {
        const fieldName = field.name;
        const fieldValue = field.value.trim();
        let errorMessage = "";

        // フィールドごとのバリデーションルール
        switch (fieldName) {
            case "name":
                if (!fieldValue) {
                    errorMessage =
                        "氏名は必須入力です。10文字以内で入力してください。";
                } else if (fieldValue.length > 10) {
                    errorMessage = "氏名は10文字以内で入力してください。";
                }
                break;

            case "furigana":
                if (!fieldValue) {
                    errorMessage =
                        "フリガナは必須入力です。10文字以内で入力してください。";
                } else if (fieldValue.length > 10) {
                    errorMessage = "フリガナは10文字以内で入力してください。";
                } else if (!/^[ァ-ヶー]+$/.test(fieldValue)) {
                    errorMessage = "フリガナはカタカナで入力してください。";
                }
                break;

            case "phone":
                if (fieldValue && !/^[0-9-]+$/.test(fieldValue)) {
                    errorMessage = "電話番号には半角数字しか入力出来ません。";
                }
                break;

            case "email":
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!fieldValue || !emailPattern.test(fieldValue)) {
                    errorMessage =
                        "メールアドレスは正しい形式でしか入力出来ません。";
                }
                break;

            case "message":
                if (!fieldValue) {
                    errorMessage = "お問い合わせ内容は必須入力です。";
                }
                break;
        }

        // エラーメッセージを表示
        this.showError(field, errorMessage);
        return !errorMessage; // エラーがない場合はtrue
    }

    // エラーメッセージの表示処理
    showError(field, message) {
        const errorElement = document.getElementById(field.name + "-error");
        if (!errorElement) return;

        // エラーメッセージを設定
        errorElement.textContent = message;

        // エラーがある場合はクラスを追加、ない場合は削除
        const hasError = !!message;
        errorElement.classList.toggle("show", hasError);
        field.classList.toggle("error", hasError);
    }

    // エラーメッセージのクリア処理
    clearError(field) {
        const errorElement = document.getElementById(field.name + "-error");

        // フィールドに値が入力されている場合のみエラーをクリア
        if (errorElement && field.value.trim()) {
            errorElement.textContent = "";
            errorElement.classList.remove("show");
            field.classList.remove("error");
        }
    }

    // フォーム全体のバリデーション（シンプル版）
    validateForm(form) {
        const requiredFields = form.querySelectorAll("[required]");
        return Array.from(requiredFields).every((field) =>
            this.validateField(field),
        );
    }

    // フォーム送信時のバリデーション（アラート表示付き）
    validateFormWithAlert(form) {
        const requiredFields = form.querySelectorAll("[required]");
        const errorMessages = [];

        // 必須フィールドのバリデーション
        requiredFields.forEach((field) => {
            const fieldName = field.name;
            const fieldValue = field.value.trim();

            // バリデーションルールを関数として定義
            const validationRules = {
                name: () => {
                    if (!fieldValue)
                        return "氏名は必須入力です。10文字以内で入力してください。";
                    if (fieldValue.length > 10)
                        return "氏名は10文字以内で入力してください。";
                    return "";
                },
                furigana: () => {
                    if (!fieldValue)
                        return "フリガナは必須入力です。10文字以内で入力してください。";
                    if (fieldValue.length > 10)
                        return "フリガナは10文字以内で入力してください。";
                    if (!/^[ァ-ヶー]+$/.test(fieldValue))
                        return "フリガナはカタカナで入力してください。";
                    return "";
                },
                email: () => {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!fieldValue || !emailPattern.test(fieldValue)) {
                        return "メールアドレスは正しい形式でしか入力出来ません。";
                    }
                    return "";
                },
                message: () => {
                    if (!fieldValue) return "お問い合わせ内容は必須入力です。";
                    return "";
                },
            };

            // エラーチェック実行
            const rule = validationRules[fieldName];
            if (rule) {
                const error = rule();
                if (error) errorMessages.push(error);
            }
        });

        // 電話番号の任意チェック
        const phoneField = form.querySelector('[name="phone"]');
        if (phoneField && phoneField.value.trim()) {
            const phoneValue = phoneField.value.trim();
            if (!/^[0-9-]+$/.test(phoneValue)) {
                errorMessages.push("電話番号には半角数字しか入力出来ません。");
            }
        }

        return {
            isValid: errorMessages.length === 0,
            errorMessage: errorMessages.join("\n"),
        };
    }

    // ログインモーダルの初期化
    initLoginModal() {
        const signinButtons = document.querySelectorAll(
            ".signin-btn, .ham-signin-btn",
        );
        const loginModal = document.getElementById("loginModal");
        const closeButton = document.getElementById("loginClose");

        if (!loginModal) return; // モーダルが存在しない場合は処理を終了

        // サインインボタンクリック時の処理
        signinButtons.forEach((button) => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                this.showLoginModal();
            });
        });

        // 閉じるボタンクリック時の処理
        if (closeButton) {
            closeButton.addEventListener("click", () => {
                this.hideLoginModal();
            });
        }

        // モーダル背景クリック時の処理（モーダル外をクリックで閉じる）
        loginModal.addEventListener("click", (e) => {
            if (e.target === loginModal) {
                this.hideLoginModal();
            }
        });

        // ESCキーでモーダルを閉じる処理
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && loginModal.classList.contains("show")) {
                this.hideLoginModal();
            }
        });
    }

    // ログインモーダルを表示
    showLoginModal() {
        const loginModal = document.getElementById("loginModal");
        if (!loginModal) return;

        // モーダルを表示
        loginModal.style.display = "flex";

        // アニメーション用の少し遅延してshowクラスを追加
        setTimeout(() => {
            loginModal.classList.add("show");
        }, 10);

        // 背景スクロールを無効化
        document.body.style.overflow = "hidden";
    }

    // ログインモーダルを非表示
    hideLoginModal() {
        const loginModal = document.getElementById("loginModal");
        if (!loginModal) return;

        // 退場アニメーション用のクラスを追加
        loginModal.classList.add("hiding");
        loginModal.classList.remove("show");

        // アニメーション完了後にモーダルを完全に非表示
        setTimeout(() => {
            loginModal.style.display = "none";
            loginModal.classList.remove("hiding");
            document.body.style.overflow = ""; // スクロール制限を解除
        }, 600); // CSS transition時間と同期
    }
}

// アプリケーションの初期化
document.addEventListener("DOMContentLoaded", () => {
    new CafeCafeApp();
});
