<link rel="icon" type="image/png" href="<?php echo e(asset('icons/logo.png')); ?>">
<script>
	(() => {
		let theme = 'light';
		try {
			theme = localStorage.getItem('rfinside-theme') === 'dark' ? 'dark' : 'light';
		} catch (error) {}

		document.documentElement.style.colorScheme = theme;

		const applyThemeToBody = () => {
			if (!document.body) return false;
			document.body.setAttribute('data-theme', theme);
			return true;
		};

		if (!applyThemeToBody()) {
			const observer = new MutationObserver(() => {
				if (applyThemeToBody()) observer.disconnect();
			});
			observer.observe(document.documentElement, { childList: true });
		}
	})();
</script>
<?php /**PATH /Volumes/shared file/4th year/capstone project/RFInsiDe/resources/views/partials/favicon.blade.php ENDPATH**/ ?>