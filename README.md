# Uroam Landing Page - Figma Design Implementation

This is a pixel-perfect implementation of the Uroam landing page design from Figma.

## Files Structure

```
PROGNET/
├── index.html          # Main HTML structure
├── style.css           # Complete CSS with exact Figma positioning
├── script.js           # JavaScript interactions
├── global.css          # Global reset and base styles
├── styleguide.css      # CSS variables for colors
├── images/             # All image assets
│   ├── logo.png
│   ├── hero-image.png
│   ├── feature-image-1.png
│   ├── feature-image-2.png
│   ├── feature-image-3.png
│   ├── avatar-1.png
│   ├── avatar-2.png
│   ├── avatar-3.png
│   └── language-selector.png
├── FIXES_CHECKLIST.md  # Detailed checklist of all fixes
└── README.md           # This file
```

## Design Specifications

- **Page Dimensions**: 1728px × 3030px
- **Font Family**: Solway (weights: 300, 400, 500, 700, 800)
- **Primary Color**: Orange #E15814
- **Text Colors**: White #FFFFFF, Black #000000
- **Background**: White #FFFFFF, Gray #F2F2F2 (testimonials)

## Key Sections

### 1. Header (138px height)
- Logo: 193×73px at x:43, y:32
- Language selector: 96×43px at x:1540, y:62
- Border: 3px solid rgba(0,0,0,0.2)

### 2. Action Buttons
- Sign in: 316×69px at x:985, y:193 (orange background)
- Join Supplier: 238×69px at x:1329, y:193 (white with orange border)
- Border radius: 45px

### 3. Hero Section (1077px height)
- Image: 1728×1077px
- Text: "Make memories." at x:56, y:562 (128px font)

### 4. Feature Sections
- Section 1: Heading at x:72, y:1253, Image at x:483, y:1508
- Section 2: Heading at x:829, y:1614, Image at x:171, y:1704
- Section 3: Heading at x:830, y:1955, Image at x:483, y:1896

### 5. Testimonials (318px height)
- Background: #F2F2F2
- 3 cards: 391×197px each, rgba(225,88,20,0.2) background
- Cards positioned at x:203, x:635, x:1067

### 6. Footer (451px height)
- Background: #E15814
- 3 columns with links
- 4 social icons: Instagram, TikTok, LinkedIn, Facebook
- Divider line and copyright

## How to Run

1. **Open in browser**: Simply open `index.html` in any modern web browser
2. **Recommended browsers**: Chrome, Firefox, Edge, Safari (latest versions)
3. **Desktop view**: Set browser width to 1728px to see exact Figma match
4. **Responsive testing**: Resize browser window to test mobile/tablet views

## Testing Checklist

- [ ] Header logo and language selector display correctly
- [ ] Action buttons positioned and styled correctly
- [ ] Hero image displays with "Make memories." overlay
- [ ] All feature sections align properly
- [ ] Feature images positioned correctly
- [ ] Testimonials display with correct avatars and ratings
- [ ] Footer columns align properly
- [ ] All 4 social icons display (Instagram, TikTok, LinkedIn, Facebook)
- [ ] Footer divider and copyright display
- [ ] Page is responsive on mobile/tablet
- [ ] Images load or show fallback placeholders

## Browser DevTools Testing

1. Open DevTools (F12)
2. Check element positions match Figma coordinates
3. Verify font sizes: 128px (hero), 60px (headings), 24px (body), etc.
4. Check colors: #E15814 (orange), #FFFFFF (white), #000000 (black)
5. Test responsive breakpoints
6. Check console for any errors

## Notes

- All positioning is relative to the 1728px wide container
- Images have SVG fallbacks if they fail to load
- Social icons are positioned absolutely within the footer-social container
- Facebook icon was added to match Figma design
- All typography uses Solway font family with exact weights from Figma

## Responsive Breakpoints

- **Desktop (1728px+)**: Exact Figma positioning
- **Desktop (1200-1727px)**: Proportional scaling
- **Tablet (<1200px)**: Flexible layout, stacked elements
- **Mobile (<768px)**: Optimized for small screens

## Support

If you notice any discrepancies with the Figma design, please check:
1. Browser zoom level (should be 100%)
2. Browser width matches design width (1728px)
3. All images are present in the `images/` folder
4. Font files are loading correctly (Solway from Google Fonts)
