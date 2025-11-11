// helpers/fbPixel.ts
export const fbPixel = {
  track(event: string, params: Record<string, any>) {
    if (window.fbq) {
      window.fbq('track', event, params);
      console.log(`[FB Pixel] Event: ${event}`, params);
    }
  }
};
