import re

# Read the file
with open('about-us.html', 'r', encoding='utf-8') as f:
    content = f.read()

# Pattern to match 4 col-lg-6 divs with item-box content
pattern = r'<div class="col-lg-6">\s*<div class="item-box">\s*<p>"My name is Thyagaraj.*?</p>\s*</div>\s*</div>\s*<div class="col-lg-6">\s*<div class="item-box">\s*<p>At Strabustech.*?</p>\s*</div>\s*</div>\s*<div class="col-lg-6">\s*<div class="item-box">\s*<p>We don.{0,2}t believe.*?</p>\s*</div>\s*</div>\s*<div class="col-lg-6">\s*<div class="item-box">\s*<p>I look forward.*?</p>\s*</div>\s*</div>'

replacement = '''<div class="col-lg-12">
        <div class="item-box">
          <p>"My name is Thyagaraj Ramachandran, and over the course of my career, I've had the privilege of working at the intersection of power systems, infrastructure, and digital innovation."</p>
          <p>At Strabustech (Strategic Business Technologies), we understand that today's organizations want more than just service—they want the most efficient, and most effective solutions available. Our leadership team and advisors have spent a large part of our careers in utilities. This deep-rooted experience allows us to understand exactly what a utility needs to succeed in a changing world.</p>
          <p>We don't believe in one-size-fits-all. Instead, we handpick domain specialists to 'stitch' together the right solution for each customer. By focusing on what each person and partner does best, we deliver technical success and tangible business value.</p>
          <p>I look forward to exploring how Strabustech can contribute to your goals in technology modernization and strategic infrastructure planning."</p>
        </div>
      </div>'''

# Replace
new_content = re.sub(pattern, replacement, content, flags=re.DOTALL)

# Write back
with open('about-us.html', 'w', encoding='utf-8') as f:
    f.write(new_content)

print('Done: Converted 4 cards to single card')
