#!/bin/bash

# Configuration
SOLR_URL="http://localhost:8983/solr/mycore/update"
TOTAL_DOCS=10000
BATCH_SIZE=1000
BATCHES=$((TOTAL_DOCS / BATCH_SIZE))

# Arrays for random data generation
BRANDS=("Apple" "Samsung" "Sony" "LG" "Dell" "HP" "Lenovo" "Asus" "Acer" "Microsoft" "Google" "Xiaomi" "Huawei" "OnePlus" "Nokia" "Motorola" "Panasonic" "Toshiba" "Canon" "Nikon")
PRODUCTS=("Laptop" "Phone" "Tablet" "Monitor" "Keyboard" "Mouse" "Headphones" "Speaker" "Camera" "Smartwatch" "TV" "Printer" "Router" "Webcam" "Microphone" "Charger" "Cable" "Case" "Stand" "Dock")
ADJECTIVES=("Pro" "Max" "Ultra" "Plus" "Lite" "Mini" "Air" "Premium" "Elite" "Advanced" "Basic" "Standard" "Deluxe" "Supreme" "Ultimate" "Classic" "Modern" "Wireless" "Gaming" "Professional")
CATEGORIES=("Electronics" "Mobile" "Computers" "Audio" "Video" "Accessories" "Gaming" "Office" "Smart Home" "Wearables")
COMMENTS=("Excellent product!" "Great value for money" "Highly recommended" "Good quality" "Fast delivery" "Works perfectly" "Amazing features" "Best purchase ever" "Very satisfied" "Could be better" "Decent product" "Not bad" "Exceeded expectations" "Worth the price" "Fantastic!")

# Function to generate a random element from an array
random_element() {
    local arr=("$@")
    echo "${arr[$RANDOM % ${#arr[@]}]}"
}

# Function to generate a random price between 10 and 5000
random_price() {
    echo "$(awk -v min=10 -v max=5000 'BEGIN{srand(); printf "%.2f\n", min+rand()*(max-min)}')"
}

# Function to generate random comments (1-3 comments)
random_comments() {
    local num_comments=$((1 + RANDOM % 3))
    local comments_json=""
    for ((i=0; i<num_comments; i++)); do
        if [ $i -gt 0 ]; then
            comments_json+=","
        fi
        comments_json+="\"$(random_element "${COMMENTS[@]}")\""
    done
    echo "$comments_json"
}

# Function to generate a batch of documents
generate_batch() {
    local start_id=$1
    local batch_size=$2
    local json='['
    
    for ((i=0; i<batch_size; i++)); do
        local id=$((start_id + i))
        local brand=$(random_element "${BRANDS[@]}")
        local product=$(random_element "${PRODUCTS[@]}")
        local adjective=$(random_element "${ADJECTIVES[@]}")
        local name="$brand $product $adjective"
        local title="$name - Model $(printf '%04d' $((RANDOM % 10000)))"
        local category=$(random_element "${CATEGORIES[@]}")
        local price=$(random_price)
        local comments=$(random_comments)
        
        if [ $i -gt 0 ]; then
            json+=','
        fi
        
        json+="{\"id\":\"$id\",\"name\":\"$name\",\"title\":\"$title\",\"category\":\"$category\",\"price\":$price,\"comments\":[$comments]}"
    done
    
    json+=']'
    echo "$json"
}

# Function to send batch to Solr
send_to_solr() {
    local json=$1
    local temp_file=$(mktemp)
    echo "$json" > "$temp_file"
    curl -s -X POST "$SOLR_URL?commit=false" \
        -H "Content-Type: application/json" \
        -d @"$temp_file" > /dev/null
    rm -f "$temp_file"
}

# Main execution
echo "Starting data generation for $TOTAL_DOCS documents..."
echo "Batch size: $BATCH_SIZE"
echo "Total batches: $BATCHES"
echo ""

start_time=$(date +%s)

for ((batch=0; batch<BATCHES; batch++)); do
    start_id=$((batch * BATCH_SIZE))
    
    # Generate and send batch
    json=$(generate_batch $start_id $BATCH_SIZE)
    send_to_solr "$json"
    
    # Progress indicator
    if [ $((batch % 10)) -eq 0 ]; then
        progress=$((batch * 100 / BATCHES))
        docs_inserted=$((batch * BATCH_SIZE))
        echo "Progress: $progress% ($docs_inserted / $TOTAL_DOCS documents)"
    fi
done

# Final commit
echo ""
echo "Committing all documents to Solr..."
curl -s -X POST "$SOLR_URL?commit=true" -H "Content-Type: application/json" -d '{}' > /dev/null

end_time=$(date +%s)
duration=$((end_time - start_time))
minutes=$((duration / 60))
seconds=$((duration % 60))

echo ""
echo "✓ Successfully inserted $TOTAL_DOCS documents!"
echo "✓ Time taken: ${minutes}m ${seconds}s"
echo ""
echo "Verify with: curl 'http://localhost:8983/solr/mycore/select?q=*:*&rows=0'"
