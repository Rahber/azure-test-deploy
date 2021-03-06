require 'stripe'
require 'sinatra'
require 'dotenv'

# Replace if using a different env file or config
ENV_PATH = '/../../../.env'.freeze
Dotenv.load(File.dirname(__FILE__) + ENV_PATH)
Stripe.api_key = ENV['STRIPE_SECRET_KEY']

set :static, true
set :public_folder, File.join(File.dirname(__FILE__), ENV['STATIC_DIR'])
set :port, 4242

get '/' do
  content_type 'text/html'
  send_file File.join(settings.public_folder, 'index.html')
end

get '/config' do
  content_type 'application/json'
  {
    publicKey: ENV['STRIPE_PUBLIC_KEY'],
    basePrice: ENV['BASE_PRICE'],
    currency: ENV['CURRENCY']
  }.to_json
end

# Fetch the Checkout Session to display the JSON result on the success page
get '/checkout-session' do
  content_type 'application/json'
  session_id = params[:sessionId]

  session = Stripe::Checkout::Session.retrieve(session_id)
  session.to_json
end

post '/create-checkout-session' do
  content_type 'application/json'
  data = JSON.parse request.body.read
  # Create new Checkout Session for the order
  # Other optional params include:
  # [billing_address_collection] - to display billing address details on the page
  # [customer] - if you have an existing Stripe Customer ID
  # [payment_intent_data] - lets capture the payment later
  # [customer_email] - lets you prefill the email input in the form
  # For full details see https:#stripe.com/docs/api/checkout/sessions/create

  # ?session_id={CHECKOUT_SESSION_ID} means the redirect will have the session ID set as a query param
  session = Stripe::Checkout::Session.create(
    success_url: ENV['DOMAIN'] + '/success.html?session_id={CHECKOUT_SESSION_ID}',
    cancel_url: ENV['DOMAIN'] + '/canceled.html',
    payment_method_types: ['card'],
    line_items: [{
      name: 'Pasha photo',
      images: ["https://picsum.photos/300/300?random=4"],
      quantity: data['quantity'],
      currency: ENV['CURRENCY'],
      amount: ENV['BASE_PRICE']
    }]
  )

  {
    sessionId: session['id']
  }.to_json
end

post '/webhook' do
  # You can use webhooks to receive information about asynchronous payment events.
  # For more about our webhook events check out https://stripe.com/docs/webhooks.
  webhook_secret = ENV['STRIPE_WEBHOOK_SECRET']
  payload = request.body.read
  if !webhook_secret.empty?
    # Retrieve the event by verifying the signature using the raw body and secret if webhook signing is configured.
    sig_header = request.env['HTTP_STRIPE_SIGNATURE']
    event = nil

    begin
      event = Stripe::Webhook.construct_event(
        payload, sig_header, webhook_secret
      )
    rescue JSON::ParserError => e
      # Invalid payload
      status 400
      return
    rescue Stripe::SignatureVerificationError => e
      # Invalid signature
      puts '⚠️  Webhook signature verification failed.'
      status 400
      return
    end
  else
    data = JSON.parse(payload, symbolize_names: true)
    event = Stripe::Event.construct_from(data)
  end
  # Get the type of webhook event sent - used to check the status of PaymentIntents.
  event_type = event['type']
  data = event['data']
  data_object = data['object']

  puts '🔔  Payment succeeded!' if event_type == 'checkout.session.completed'

  content_type 'application/json'
  {
    status: 'success'
  }.to_json
end
