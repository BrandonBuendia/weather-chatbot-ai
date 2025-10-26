import { ref, watch, onMounted } from 'vue';

const isDark = ref(false);

export function useTheme() {
    const toggleTheme = (event) => {
        const x = event?.clientX ?? window.innerWidth / 2;
        const y = event?.clientY ?? window.innerHeight / 2;

        const endRadius = Math.hypot(
            Math.max(x, window.innerWidth - x),
            Math.max(y, window.innerHeight - y)
        );

        const clipPath = [
            `circle(0px at ${x}px ${y}px)`,
            `circle(${endRadius}px at ${x}px ${y}px)`
        ];

        const isDarkMode = !isDark.value;

        if (!document.startViewTransition) {
            updateTheme(isDarkMode);
            return;
        }

        const transition = document.startViewTransition(() => {
            updateTheme(isDarkMode);
        });

        transition.ready.then(() => {
            document.documentElement.animate(
                {
                    clipPath: isDarkMode ? clipPath : [...clipPath].reverse()
                },
                {
                    duration: 500,
                    easing: 'ease-in-out',
                    pseudoElement: isDarkMode
                        ? '::view-transition-new(root)'
                        : '::view-transition-old(root)'
                }
            );
        });
    };

    const updateTheme = (dark) => {
        isDark.value = dark;

        if (dark) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    };

    const initTheme = () => {
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        const shouldBeDark = savedTheme === 'dark' || (!savedTheme && prefersDark);
        updateTheme(shouldBeDark);
    };

    onMounted(() => {
        initTheme();
    });

    return {
        isDark,
        toggleTheme
    };
}

// cGFuZ29saW4=
