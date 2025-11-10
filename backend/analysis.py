import sys
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer

# Get input from PHP
if len(sys.argv) > 1:
    analyzer = SentimentIntensityAnalyzer()
    text = sys.argv[1]
    type = sys.argv[2]
    analysis = analyzer.polarity_scores(text)
else:
    user_input = "No input provided"

# Process input (example: make text uppercase)
output = analysis.get(type)

# Print the output (this gets captured by PHP)
print(output)
